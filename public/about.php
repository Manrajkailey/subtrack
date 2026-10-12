<?php
require_once __DIR__ . '/bootstrap.php';

$pageTitle = 'About Us';
$activePublicNav = 'about';
require __DIR__ . '/partials/site_header.php';

$tick = '<svg viewBox="0 0 20 20" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"><path d="M4 10l4 4 8-8"/></svg>';
?>

<!-- ============ Hero ============ -->
<section class="hero hero-compact">
    <div class="container">
        <div>
            <span class="eyebrow"><span class="dot"></span>About SubTrack</span>
            <h1>Built to make <span class="hl">recurring revenue</span> easy to understand</h1>
            <p class="lead">
                SubTrack is a Business Subscription Revenue Tracker — a focused system that helps small
                businesses manage subscription customers and see exactly how much predictable revenue
                they earn, month after month.
            </p>
            <div class="hero-actions">
                <a href="register.php" class="btn btn-primary btn-lg">Get started</a>
                <a href="contact.php" class="btn btn-ghost btn-lg">Contact us</a>
            </div>
        </div>
        <div class="collage">
            <img class="main-img" src="assets/img/photos/modern-office.jpg" width="1200" height="801" alt="A bright, modern open-plan office with long work tables">
            <img class="inset-img" src="assets/img/photos/desk-overhead.jpg" width="1400" height="1074" alt="Overhead view of a person working on a laptop with a notebook">
            <div class="float-card">
                <div class="k">Metrics tracked automatically</div>
                <div class="v">MRR · ARR · ARPU · Churn</div>
            </div>
        </div>
    </div>
</section>

<!-- ============ Stats ============ -->
<section class="section-sm" style="padding-top:10px;">
    <div class="container">
        <div class="stats-row">
            <div class="stat"><div class="v">5</div><div class="k">Core modules</div></div>
            <div class="stat"><div class="v">4</div><div class="k">Key revenue metrics</div></div>
            <div class="stat"><div class="v">1-click</div><div class="k">Renew or cancel</div></div>
            <div class="stat"><div class="v">100%</div><div class="k">Private per account</div></div>
        </div>
    </div>
</section>

<!-- ============ Story ============ -->
<section class="section band">
    <div class="container">
        <div class="photo-feature reverse">
            <div class="pf-media">
                <img src="assets/img/photos/team-meeting.jpg" width="1400" height="1050" loading="lazy" alt="A small team discussing work together around their desks">
            </div>
            <div class="pf-text">
                <span class="eyebrow"><span class="dot"></span>Our story</span>
                <h2>Spreadsheets weren't built for subscriptions</h2>
                <p>
                    SubTrack started with a simple observation: many small businesses with a subscription model —
                    gyms, tutoring centres, local software vendors, meal-plan services — track their customers in
                    spreadsheets. That works for a while, but a spreadsheet won't tell you your Monthly Recurring
                    Revenue, won't warn you when a renewal is due, and won't show which plan is driving growth.
                </p>
                <p>
                    So we built the smallest possible system that solves exactly that problem, without the
                    complexity of a full accounting suite.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============ What we measure ============ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>The numbers that describe a healthy subscription business</h2>
            <p>From four simple things — customers, plans, subscriptions and payments — SubTrack calculates these for you, in the open.</p>
        </div>
        <div class="metric-cards">
            <div class="metric-card">
                <div class="abbr">MRR</div>
                <h4>Monthly Recurring Revenue</h4>
                <p>The predictable revenue you collect every month from active subscriptions.</p>
                <div class="formula">Σ active prices (annual ÷ 12)</div>
            </div>
            <div class="metric-card">
                <div class="abbr">ARR</div>
                <h4>Annual Recurring Revenue</h4>
                <p>Your MRR projected across a full year — the headline growth number.</p>
                <div class="formula">MRR × 12</div>
            </div>
            <div class="metric-card">
                <div class="abbr">ARPU</div>
                <h4>Average Revenue Per User</h4>
                <p>How much the average active subscriber is worth to you each month.</p>
                <div class="formula">MRR ÷ active subscribers</div>
            </div>
            <div class="metric-card">
                <div class="abbr">Churn</div>
                <h4>Churn &amp; renewal rate</h4>
                <p>How many customers leave versus stay — the early warning for your revenue.</p>
                <div class="formula">cancelled ÷ (active + cancelled)</div>
            </div>
        </div>
    </div>
</section>

<!-- ============ Values ============ -->
<section class="section band">
    <div class="container">
        <div class="section-head"><h2>How we think about it</h2></div>
        <div class="feature-grid">
            <div class="feature">
                <div class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg></div>
                <h3>Transparent by default</h3>
                <p>Every metric is calculated from your own data, with the formula shown. Nothing is estimated or hidden in a black box.</p>
            </div>
            <div class="feature">
                <div class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg></div>
                <h3>Private per business</h3>
                <p>Each account is fully separate. Your customers, plans and numbers are never visible to anyone else on the platform.</p>
            </div>
            <div class="feature">
                <div class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L4 14h7l-1 8 9-12h-7z"/></svg></div>
                <h3>Small and focused</h3>
                <p>A few things done well: manage subscriptions, calculate revenue honestly and surface renewals before they're missed.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ What's next ============ -->
<section class="section">
    <div class="container">
        <div class="photo-feature">
            <div class="pf-media">
                <img src="assets/img/photos/laptop-tea.jpg" width="1400" height="1000" loading="lazy" alt="A laptop on a wooden desk next to a cup of tea and a notebook">
            </div>
            <div class="pf-text">
                <span class="eyebrow"><span class="dot"></span>What's next</span>
                <h2>Always improving</h2>
                <p>We're continuing to refine SubTrack's reporting and renewal tools based on real use.</p>
                <ul class="check-list">
                    <li><span class="tick"><?= $tick ?></span><span><b>Revenue goals</b> with progress tracking</span></li>
                    <li><span class="tick"><?= $tick ?></span><span><b>Exportable reports</b> for your accountant</span></li>
                    <li><span class="tick"><?= $tick ?></span><span><b>Renewal reminders</b> sent to customers</span></li>
                </ul>
                <p>Have an idea, or something about the numbers that doesn't make sense? We'd like to hear it.</p>
                <a href="contact.php" class="btn btn-primary">Share feedback</a>
            </div>
        </div>
    </div>
</section>

<section class="section-sm">
    <div class="container">
        <div class="cta-band">
            <h2>See it for yourself</h2>
            <p>Create a free account and explore the dashboard, revenue analytics and renewal tools first-hand.</p>
            <div class="actions">
                <a href="register.php" class="btn btn-light btn-lg">Create your account</a>
                <a href="login.php" class="btn btn-line btn-lg">Try the demo</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/site_footer.php'; ?>
