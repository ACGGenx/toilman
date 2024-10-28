<x-guest-layout>
    <section class="login-content">
        <div class="row m-0 align-items-center bg-white vh-100">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Bulk Enquiry</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form id="enquiryForm" action="{{ route('enquiry.submit-bulk') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="detail">Detail</label>
                                        <textarea class="form-control" id="detail" name="detail" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_id">Select Product</label>
                                        <select class="form-control" id="product_id" name="product_id" required>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Google reCAPTCHA -->
                                    <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                    </div>
                                    <button type="submit" id="submitButton" class="btn btn-primary">Submit Enquiry</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function disableButton() {
            var submitButton = document.getElementById('submitButton');
            submitButton.disabled = true; // Disable the button
            submitButton.textContent = 'Submitting...'; // Optional: Change the button text
        }
        // Prevent form submission until reCAPTCHA is verified
        document.getElementById('enquiryForm').addEventListener('submit', function(event) {
            var recaptchaResponse = grecaptcha.getResponse();
            if (recaptchaResponse.length === 0) {
                event.preventDefault(); // Prevent form submission
                Swal.fire({
                    title: 'Error',
                    text: 'Please complete the reCAPTCHA before submitting the form.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            else{
                disableButton();
            }
        });

        // Trigger SweetAlert2 popup if success message exists in the session
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
        @endif
    </script>

</x-guest-layout>
