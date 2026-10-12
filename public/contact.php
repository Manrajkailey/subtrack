<?php
require_once __DIR__ . '/bootstrap.php';

$pageTitle = 'Contact Us';
$activePublicNav = 'contact';
require __DIR__ . '/partials/site_header.php';
?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow"><span class="dot"></span>We're here to help</span>
        <h1>Let's talk about your <span class="hl">subscriptions</span></h1>
        <p class="lead">Questions, ideas or something not working the way you expected? Send us a message — we read every one.</p>
    </div>
</section>

<section class="section-sm" style="padding-top:36px;">
    <div class="container">
        <div class="contact-layout">
            <div class="contact-photo">
                <img src="assets/img/photos/laptop-desk.jpg" width="1400" height="934" alt="A laptop and a white chair at a wooden desk in a quiet workspace">
                <div class="overlay"></div>
                <div class="content">
                    <h3>Get in touch</h3>
                    <p>Reach us directly, or use the form — it opens a ready-to-send email.</p>
                    <div class="contact-lines">
                        <div class="contact-line">
                            <span class="ic"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></span>
                            <div><strong>support@subtrack.example</strong><span>Setup help, bugs and general questions</span></div>
                        </div>
                        <div class="contact-line">
                            <span class="ic"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></span>
                            <div><strong>Replies within 1–2 business days</strong><span>Monday to Friday, 10 am – 6 pm IST</span></div>
                        </div>
                        <div class="contact-line">
                            <span class="ic"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#fff" stroke-width="2"><path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg></span>
                            <div><strong>Want to look around first?</strong><span>Use the demo login on the Sign in page</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h2>Send us a message</h2>
                <p>Fill this in and we'll open a pre-filled email in your mail app — nothing is sent from this page directly.</p>
                <form id="contactForm">
                    <div class="two-col">
                        <div class="field">
                            <label for="c_name">Your name</label>
                            <input type="text" id="c_name" required placeholder="Full name">
                        </div>
                        <div class="field">
                            <label for="c_email">Email</label>
                            <input type="email" id="c_email" required placeholder="you@business.com">
                        </div>
                    </div>
                    <div class="field">
                        <label for="c_business">Business name <small>(optional)</small></label>
                        <input type="text" id="c_business" placeholder="e.g. FitZone Gym">
                    </div>
                    <div class="field">
                        <label>What's it about?</label>
                        <div class="topic-chips">
                            <label><input type="radio" name="topic" value="General question" checked><span>General question</span></label>
                            <label><input type="radio" name="topic" value="Setup / installation help"><span>Setup help</span></label>
                            <label><input type="radio" name="topic" value="Bug report"><span>Report a bug</span></label>
                            <label><input type="radio" name="topic" value="Feature request"><span>Feature idea</span></label>
                        </div>
                    </div>
                    <div class="field">
                        <label for="c_message">Message</label>
                        <textarea id="c_message" rows="5" required placeholder="Tell us what's going on…"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Send message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="section-sm">
    <div class="container">
        <div class="info-cards">
            <div class="feature">
                <div class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a4 4 0 00-5.4 5.4L3 18v3h3l6.3-6.3a4 4 0 005.4-5.4l-2.5 2.5-2.4-.6-.6-2.4z"/></svg></div>
                <h3>Setting up SubTrack</h3>
                <p>Need a hand installing it or importing the database? Tell us your setup and we'll walk you through it.</p>
            </div>
            <div class="feature">
                <div class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9L2.6 18a1.8 1.8 0 001.6 2.7h15.6a1.8 1.8 0 001.6-2.7L13.7 3.9a1.8 1.8 0 00-3.4 0z"/></svg></div>
                <h3>Reporting a bug</h3>
                <p>Include the page you were on, what you clicked and any message you saw — it helps us fix things faster.</p>
            </div>
            <div class="feature">
                <div class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18h6M10 21h4M12 3a6 6 0 00-3.5 10.9c.6.4 1 1.1 1 1.9v.2h5v-.2c0-.8.4-1.5 1-1.9A6 6 0 0012 3z"/></svg></div>
                <h3>Suggesting a feature</h3>
                <p>A report or metric you wish SubTrack showed? We plan what's next based on these requests.</p>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var name = document.getElementById('c_name').value;
    var email = document.getElementById('c_email').value;
    var business = document.getElementById('c_business').value;
    var topic = document.querySelector('input[name="topic"]:checked').value;
    var message = document.getElementById('c_message').value;

    var body = 'From: ' + name + ' (' + email + ')' + (business ? '\nBusiness: ' + business : '') + '\n\n' + message;
    window.location.href = 'mailto:support@subtrack.example'
        + '?subject=' + encodeURIComponent('[SubTrack] ' + topic)
        + '&body=' + encodeURIComponent(body);
});
</script>

<?php require __DIR__ . '/partials/site_footer.php'; ?>
