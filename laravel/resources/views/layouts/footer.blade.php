<footer class="container-fluid {{ $fixedBottom ?? 'fixed-bottom' }}" style="background-color: rgba(2, 2, 38, 0.427)">
    <div class="row d-flex align-content-center justify-content-around align-items-center p-3">
        <div class="col-md-3 align-content-center align-items-center justify-content-center">
            <p style="font-size: 1.3rem">&copy; Pet Shelter - Piotr Smoła 2024</p>
        </div>
        <div class="col-md-3 mb-2 mb-md-0 align-content-center align-items-center justify-content-center" style="font-size: 1.07rem">
            <a href="mailto:petshelter@example.com" class="text-decoration-none text-white">
                <i class="bi bi-envelope-fill" style="font-size: 20px;"></i> petshelter@example.com
            </a>
            <p>1234 Shelter St, Animal City, AC 56789</p>
        </div>
        <div class="col-md-3 d-flex align-content-center align-items-center justify-content-center">
            <a href="https://facebook.com" target="_blank" class="text-decoration-none text-white me-3">
                <i class="bi bi-facebook" style="font-size: 24px;"></i>
            </a>
            <a href="https://twitter.com" target="_blank" class="text-decoration-none text-white me-3">
                <i class="bi bi-twitter" style="font-size: 24px;"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="text-decoration-none text-white me-3">
                <i class="bi bi-instagram" style="font-size: 24px;"></i>
            </a>
            <a href="https://tiktok.com" target="_blank" class="text-decoration-none text-white">
                <i class="bi bi-tiktok" style="font-size: 24px;"></i>
            </a>
        </div>
        <div class="col-md-3">
            <div class="col-12 text-center">
                <h5>Opening Hours</h5>
                <p>Mon - Fri: 9:00 AM - 6:00 PM</p>
                <p class="mb-0">Sat - Sun: 10:00 AM - 4:00 PM</p>
            </div>
        </div>
    </div>
</footer>

<script>
    window.onload = function(e) {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl)
        });
        toastList.forEach(toast => toast.show());
    }
</script>
