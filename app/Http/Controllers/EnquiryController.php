<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\EnquirySubmitted;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public $isView;

    public function __construct()
    {
        // Ensure auth()->user() can be accessed
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            $this->isView = $user->hasRole('admin') || $user->isViewPermission(1);

            return $next($request);
        });
    }
    // Display list of products for enquiry
    public function index()
    {
        $products = Product::all();
        return view('enquiries.index', compact('products'));
    }

    // Show Bulk Enquiry Form
    public function showBulkEnquiryForm()
    {
        $products = Product::all();
        return view('enquiries.bulk', compact('products'));
    }

    // Handle Normal Enquiry Submission
    public function submitEnquiry(Request $request)
    {
        $this->validateRecaptcha($request);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'detail' => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);

        // Prepare data for the enquiry
        $data = $request->all();
        $data['type'] = 'normal';

        // Store the enquiry
        $enquiry = Enquiry::create($data);

        // Send email if needed (currently commented out)

        Mail::to($enquiry->email)->send(new EnquirySubmitted($enquiry));

        // Redirect with success message
        return redirect()->route('enquiries.index')->with('success', 'Enquiry submitted successfully!');
    }

    // Handle Bulk Enquiry Submission
    public function submitBulkEnquiry(Request $request)
    {

        $this->validateRecaptcha($request);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'detail' => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);

        // Prepare data for the bulk enquiry
        $data = $request->all();
        $data['type'] = 'bulk';

        // Store the bulk enquiry
        $enquiry = Enquiry::create($data);

        // Send email if needed (currently commented out)

        Mail::to($enquiry->email)->send(new EnquirySubmitted($enquiry));

        // Redirect with success message
        return redirect()->route('enquiries.bulk')->with('success', 'Bulk enquiry submitted successfully!');
    }


    private function validateRecaptcha(Request $request)
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');

        // Send a request to Google's reCAPTCHA verification API
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $body = json_decode($response->getBody(), true);

        // Log the reCAPTCHA response for debugging
        Log::info('reCAPTCHA Verification Response:', $body);

        // Bypass hostname check in local environment
        if (app()->environment('local')) {
            $hostname = $body['hostname'] ?? '';
            if ($hostname !== 'localhost') {
                return back()->withErrors(['g-recaptcha-response' => 'Invalid reCAPTCHA hostname.']);
            }
        }
        // Check if reCAPTCHA validation failed
        if (!$body['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
        }
    }

    // List Enquiries with optional filters (date range, type)
    public function listEnquiries(Request $request)
    {
        if ($this->isView) {
            $query = Enquiry::with('product')->orderBy('created_at', 'desc');

            // Apply date range filter if provided
            if ($request->filled('start_date') || $request->filled('end_date')) {
                $start_date = $request->filled('start_date') ? $request->start_date . ' 00:00:00' : '1970-01-01 00:00:00';
                $end_date = $request->filled('end_date') ? $request->end_date . ' 23:59:59' : now()->format('Y-m-d H:i:s');
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }

            // Apply type filter if provided
            if ($request->filled('type')) {
                $query->whereIn('type', $request->type);
            }

            // Get the filtered enquiries
            $enquiries = $query->get();
            return view('enquiries.list', ['enquiries' => $enquiries]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view enquiries.');
    }

    // Download Enquiries as CSV with optional filters (date range, type)
    public function downloadCSV(Request $request)
    {
        if ($this->isView) {
            $query = Enquiry::with('product');

            // Apply date range filter if provided
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            if ($start_date || $end_date) {
                $start_date = $start_date ? $start_date . ' 00:00:00' : '1970-01-01 00:00:00';
                $end_date = $end_date ? $end_date . ' 23:59:59' : now()->format('Y-m-d H:i:s');
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }

            // Apply type filter if provided
            $types = $request->input('type');
            if ($types) {
                $query->whereIn('type', (array)$types);
            }

            $enquiries = $query->get();

            // Set CSV filename
            $csvFileName = 'enquiries_' . now()->format('Y-m-d_H-i-s') . '.csv';

            // Create CSV output
            $handle = fopen('php://output', 'w');

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

            // Add CSV header row
            fputcsv($handle, ['ID', 'Name', 'Email', 'Detail', 'Product', 'Type', 'Created At']);

            // Add enquiry data rows
            foreach ($enquiries as $enquiry) {
                fputcsv($handle, [
                    $enquiry->id,
                    $enquiry->name,
                    $enquiry->email,
                    $enquiry->detail,
                    $enquiry->product->name ?? 'N/A',
                    $enquiry->type == 'normal' ? 'Normal' : 'Bulk',
                    $enquiry->created_at->format('d M Y'),
                ]);
            }

            fclose($handle);
            exit;
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view enquiries.');
    }
}
