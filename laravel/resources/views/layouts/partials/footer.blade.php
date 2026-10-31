<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            <div>
                <a class="site-brand" href="{{ route('index') }}">Pet Shelter</a>
                <p>A small shelter for dogs and cats looking for a second home.</p>
            </div>
            <div>
                <h2 class="site-footer__title">Visit us</h2>
                <p>1234 Shelter St, Animal City, AC 56789</p>
                <p><a href="mailto:petshelter@example.com">petshelter@example.com</a></p>
            </div>
            <div>
                <h2 class="site-footer__title">Opening hours</h2>
                <p>Mon–Fri: 9:00–18:00</p>
                <p>Sat–Sun: 10:00–16:00</p>
            </div>
            <div>
                <h2 class="site-footer__title">Follow us</h2>
                <div class="site-footer__social">
                    <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
                    <a href="https://x.com" target="_blank" rel="noopener" aria-label="X (Twitter)"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
                    <a href="https://tiktok.com" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="site-footer__bottom">
            <span>&copy; {{ date('Y') }} Pet Shelter – Piotr Smoła</span>
            <ul>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('regulations') }}">Regulations</a></li>
            </ul>
        </div>
    </div>
</footer>
