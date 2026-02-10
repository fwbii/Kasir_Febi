<style>
    .footer {
        background: #fff;
        padding: 1.5rem;
        border-top: 1px solid #edf2f9;
        color: #8898aa;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.875rem;
    }

    .footer-links a {
        color: #5e72e4;
        text-decoration: none;
        margin-left: 15px;
        font-weight: 500;
        transition: color 0.2s;
    }

    .footer-links a:hover {
        color: #324cdd;
    }

    .clock-display {
        font-weight: 600;
        color: #525f7f;
        background: #f8f9fe;
        padding: 4px 12px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
</style>

<footer class="footer mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-lg-start">
                    &copy; <?= date('Y'); ?> 
                    <span class="fw-bold text-primary">Kasir FWBI </span>. 
                    Made with <i class="fa fa-heart text-danger"></i> for Better Business.
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end footer-links">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-muted">Version UKK FEBI</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const timeString = `${hours}:${minutes}:${seconds}`;
        document.getElementById('realtime-clock').innerHTML = `<i class="fa-regular fa-clock me-2"></i>${timeString}`;
    }

    setInterval(updateClock, 1000);
    updateClock(); // Jalankan langsung saat load
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>