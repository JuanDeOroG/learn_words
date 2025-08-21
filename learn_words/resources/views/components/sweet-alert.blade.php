@if (session('success') || session('error') || session('info') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'center',
                    showConfirmButton: false,
                    width: 300
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    title: 'Error',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    timer: 7000,
                    timerProgressBar: true,
                    position: 'center',
                    showConfirmButton: false,
                    width: 300
                });
            @endif
            @if (session('info'))
                Swal.fire({
                    title: 'Info',
                    text: "{{ session('info') }}",
                    icon: 'info',
                    timer: 5000,
                    timerProgressBar: true,
                    position: 'center',
                    showConfirmButton: false,
                    width: 300
                });
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    Swal.fire({
                        title: 'Validation Error',
                        text: "{{ $error }}",
                        icon: 'error',
                        timer: 7000,
                        timerProgressBar: true,
                        position: 'center',
                        showConfirmButton: false,
                        width: 300
                    });
                @endforeach
            @endif
        });
    </script>
@endif