<?php
require_once __DIR__ . '/bootstrap.php';

if ($authService->currentUser() !== null) {
    redirect('dashboard.php');
}

$pageTitle = APP_TAGLINE;
$activePublicNav = 'home';
require __DIR__ . '/partials/site_header.php';

$tick = '<svg viewBox="0 0 20 20" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"><path d="M4 10l4 4 8-8"/></svg>';
?>

<!-- ============ Hero ============ -->
<section class="hero">
    <div class="container">
        <div>
            <span class="eyebrow"><span class="dot"></span>Subscription analytics for small businesses</span>
            <h1>Know your <span class="hl">recurring revenue</span> at a glance</h1>
            <p class="lead">
                SubTrack turns your customers, plans and payments into the numbers that matter —
                MRR, ARR, churn and upcoming renewals — in one clean dashboard. No spreadsheets.
            </p>
            <div class="hero-actions">
                <a href="register.php" class="btn btn-primary btn-lg">Start tracking free</a>
                <a href="login.php" class="btn btn-ghost btn-lg">View live demo</a>
            </div>
            <div class="hero-notes">
                <span><?= $tick ?> Free to use</span>
                <span><?= $tick ?> Set up in 5 minutes</span>
                <span><?= $tick ?> Your data stays private</span>
            </div>
        </div>

        <div class="hero-visual">
            <div class="blob" aria-hidden="true"></div>
            <div class="browser-frame">
                <div class="browser-bar"><i></i><i></i><i></i><span class="url">subtrack.local/dashboard</span></div>
                <img src="assets/img/screens/dashboard.png"
                     width="1280" height="760"
                     alt="The SubTrack dashboard showing total revenue, MRR, active subscribers, a six-month revenue chart and plan breakdown">
            </div>
            <div class="float-card fc-1">
                <div class="k">Monthly Recurring Revenue</div>
                <div class="v">₹31,468</div>
                <div class="d">▲ 12.4% vs last month</div>
            </div>
            <div class="float-card fc-2">
                <div class="avatar-ic"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg></div>
                <div>
                    <div class="k">New subscriber</div>
                    <div style="font-weight:700; color:var(--heading); font-size:14px;">Premium plan · ₹1,499/mo</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ Built for ============ -->
<section class="builtfor">
    <div class="container">
        <div class="label">Made for any business that bills on a schedule</div>
        <div class="chips">
            <span class="chip"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8v8M18 8v8M3 10v4M21 10v4M6 12h12"/></svg>Gyms</span>
            <span class="chip"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 8l10-5 10 5-10 5z"/><path d="M6 10.5V16c3 2 9 2 12 0v-5.5"/></svg>Tuition centres</span>
            <span class="chip"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>SaaS tools</span>
            <span class="chip"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 11h16a8 8 0 01-16 0zM12 3v3M8 4v2M16 4v2"/></svg>Meal plans</span>
            <span class="chip"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4L8.1 15.9M14.5 14.5L20 20M8.1 8.1L12 12"/></svg>Salons</span>
            <span class="chip"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V5a2 2 0 012-2h12v18H6a2 2 0 01-2-2zM4 19a2 2 0 012-2h12"/></svg>Libraries &amp; clubs</span>
        </div>
    </div>
</section>

<!-- ============ Feature widgets, row 1 ============ -->
<section class="section" id="features">
    <div class="container">
        <div class="section-head">
            <h2>Everything your subscription business runs on</h2>
            <p>See what's coming in, what's going out and who needs attention — without opening a single spreadsheet.</p>
        </div>

        <div class="widgets">
            <div class="widget">
                <div class="widget-label c1"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></span>Control Center</div>
                <div class="widget-card">
                    <div class="breakdown-head"><span>Breakdown</span><span>This month</span></div>
                    <div class="breakdown-row"><span class="n">18</span><span class="l">New subscriptions</span><span class="a pos">+₹14,860</span></div>
                    <div class="breakdown-row"><span class="n">9</span><span class="l">Renewals</span><span class="a pos">+₹8,091</span></div>
                    <div class="breakdown-row"><span class="n">4</span><span class="l">Upgrades</span><span class="a pos">+₹2,400</span></div>
                    <div class="breakdown-row"><span class="n">3</span><span class="l">Cancellations</span><span class="a neg">−₹2,697</span></div>
                    <div class="breakdown-row"><span class="n">1</span><span class="l">Downgrades</span><span class="a neg">−₹500</span></div>
                    <div class="breakdown-foot"><span>Net new MRR</span><strong>+₹22,154</strong></div>
                </div>
                <p class="widget-caption">The pulse of your business — visible at a glance and broken down line by line.</p>
            </div>

            <div class="widget">
                <div class="widget-label c2"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg></span>Dashboard</div>
                <div class="widget-card">
                    <div class="wc-title">Monthly Recurring Revenue</div>
                    <div class="wc-big">₹31,468</div>
                    <svg class="spark" viewBox="0 0 300 150" preserveAspectRatio="none" aria-hidden="true">
                        <defs><linearGradient id="sparkFill" x1="0" x2="0" y1="0" y2="1"><stop offset="0" style="stop-color:var(--chart-2); stop-opacity:0.28"/><stop offset="1" style="stop-color:var(--chart-2); stop-opacity:0"/></linearGradient></defs>
                        <path d="M0 122 L25 116 L50 119 L75 104 L100 108 L125 92 L150 96 L175 78 L200 82 L225 60 L250 52 L275 40 L300 26 L300 150 L0 150 Z" fill="url(#sparkFill)"/>
                        <path d="M0 122 L25 116 L50 119 L75 104 L100 108 L125 92 L150 96 L175 78 L200 82 L225 60 L250 52 L275 40 L300 26" fill="none" style="stroke:var(--chart-2)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
                        <circle cx="300" cy="26" r="5" style="fill:var(--chart-2)"/>
                    </svg>
                    <div class="mini-axis"><span>Oct</span><span>Jan</span><span>Apr</span><span>Jul</span><span>Sep</span></div>
                </div>
                <p class="widget-caption">Track MRR, ARR and ARPU month by month and see exactly where growth comes from.</p>
            </div>

            <div class="widget">
                <div class="widget-label c3"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 3a6 6 0 016 6v3.5l1.5 3.5h-15L6 12.5V9a6 6 0 016-6z"/><path d="M10 19a2 2 0 004 0"/></svg></span>Renewals</div>
                <div class="widget-card">
                    <div class="breakdown-head"><span>Due this week</span><span>5 of 35</span></div>
                    <div class="renewal-row"><span class="av" style="background:var(--chart-1)">AK</span><span class="who">Aman Kapoor<small>Premium · ₹1,499</small></span><span class="pill late">Overdue</span></div>
                    <div class="renewal-row"><span class="av" style="background:var(--chart-2)">PS</span><span class="who">Priya Sharma<small>Basic · ₹499</small></span><span class="pill due">Tomorrow</span></div>
                    <div class="renewal-row"><span class="av" style="background:var(--chart-3)">RV</span><span class="who">Rohit Verma<small>Pro · ₹999</small></span><span class="pill due">In 3 days</span></div>
                    <div class="renewal-row"><span class="av" style="background:var(--chart-4)">NK</span><span class="who">Neha Kaur<small>Annual Pro · ₹9,999</small></span><span class="pill ok">In 6 days</span></div>
                </div>
                <p class="widget-caption">Never miss a renewal — see who's due soon and who's overdue, then renew in one click.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ Split intro + widgets, row 2 ============ -->
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="split-intro">
            <h2>Make better decisions by knowing what drives your growth.</h2>
            <p>Most small businesses only find out how they did at the end of the month. SubTrack updates every metric the moment you add a customer, renew a plan or record a cancellation — so you always know where you stand.</p>
        </div>

        <div class="widgets">
            <div class="widget">
                <div class="widget-label c4"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M6 20V10M12 20V4M18 20v-7"/></svg></span>Churn</div>
                <div class="widget-card">
                    <div class="wc-title">Churn rate (30 days)</div>
                    <div class="wc-big success">2.8%</div>
                    <div class="bench-row"><span>High</span><span class="track"><b style="width:92%"></b></span><span>11%</span></div>
                    <div class="bench-row"><span>Typical</span><span class="track"><b style="width:62%"></b></span><span>7%</span></div>
                    <div class="bench-row"><span>Healthy</span><span class="track"><b style="width:38%"></b></span><span>4%</span></div>
                    <div class="bench-row you"><span>You</span><span class="track"><b style="width:25%"></b></span><span>2.8%</span></div>
                </div>
                <p class="widget-caption">Spot customers slipping away early and keep your renewal rate high.</p>
            </div>

            <div class="widget">
                <div class="widget-label c1"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.2"/></svg></span>Goals</div>
                <div class="widget-card">
                    <div class="wc-title">Q4 MRR goal</div>
                    <div class="wc-big">₹45,000</div>
                    <div class="goal-meta"><span>₹31,468 now</span><span>31 Dec</span></div>
                    <div class="goal-track"><b style="width:70%"></b><i style="left:64%"></i></div>
                    <div class="goal-stats">
                        <div><div class="k">Needed / month</div><div class="v">₹4,511</div></div>
                        <div><div class="k">On track</div><div class="v" style="color:var(--success)">Yes</div></div>
                    </div>
                </div>
                <p class="widget-caption">See how far you are from your revenue target and what it takes to get there.</p>
            </div>

            <div class="widget">
                <div class="widget-label c5"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg></span>Monthly reports</div>
                <div class="widget-card">
                    <div class="wc-title">Revenue by month</div>
                    <div class="wc-big">₹1,71,369</div>
                    <div class="mini-bars stacked" aria-hidden="true">
                        <span style="height:28%"></span><span style="height:34%"></span><span style="height:31%"></span><span style="height:44%"></span>
                        <span style="height:49%"></span><span style="height:46%"></span><span style="height:60%"></span><span style="height:66%"></span>
                        <span style="height:58%"></span><span style="height:78%"></span><span style="height:86%"></span><span style="height:96%"></span>
                    </div>
                    <div class="mini-axis"><span>Oct</span><span>Jan</span><span>Apr</span><span>Jul</span><span>Sep</span></div>
                </div>
                <p class="widget-caption">Pick any month for a summary of revenue, new subscriptions and cancellations.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ Photo feature ============ -->
<section class="section band">
    <div class="container">
        <div class="photo-feature">
            <div class="pf-media">
                <img src="assets/img/photos/team-laptops.jpg" width="1400" height="934" loading="lazy"
                     alt="A small team working together on laptops around a shared table">
                <div class="float-card">
                    <div class="k">Renewal rate</div>
                    <div class="v">97.2%</div>
                    <div class="d">▲ 1.6 pts this quarter</div>
                </div>
            </div>
            <div class="pf-text">
                <span class="eyebrow"><span class="dot"></span>Built for small teams</span>
                <h2>Take the busywork out of subscription billing</h2>
                <p>SubTrack gives owners and their teams one shared place to manage every subscriber, from the first payment to the hundredth renewal.</p>
                <ul class="check-list">
                    <li><span class="tick"><?= $tick ?></span><span><b>Customers, plans and subscriptions</b> in one organised place, with simple add, edit and delete screens.</span></li>
                    <li><span class="tick"><?= $tick ?></span><span><b>Renewal dates calculated for you</b> — monthly or annual, the first payment is logged automatically.</span></li>
                    <li><span class="tick"><?= $tick ?></span><span><b>One-click renew or cancel</b> keeps MRR, churn and reports accurate in real time.</span></li>
                    <li><span class="tick"><?= $tick ?></span><span><b>Private by design</b> — each business account only ever sees its own data.</span></li>
                </ul>
                <a href="register.php" class="btn btn-primary">Create your account</a>
            </div>
        </div>
    </div>
</section>

<!-- ============ Dashboard showcase ============ -->
<section class="section" id="dashboard">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow"><span class="dot"></span>The real product</span>
            <h2>Your whole business in one dashboard</h2>
            <p>These are actual screens from SubTrack, filled with demo data. Switch between them below.</p>
        </div>
        <div class="showcase">
            <div class="showcase-tabs" role="tablist">
                <button type="button" class="is-active" data-shot-tab="dashboard" role="tab" aria-selected="true">Dashboard</button>
                <button type="button" data-shot-tab="revenue" role="tab" aria-selected="false">Revenue analytics</button>
                <button type="button" data-shot-tab="subscriptions" role="tab" aria-selected="false">Subscriptions</button>
            </div>
            <div class="browser-frame">
                <div class="browser-bar"><i></i><i></i><i></i><span class="url" id="showcaseUrl">subtrack.local/dashboard</span></div>
                <img id="showcaseImg" src="assets/img/screens/dashboard.png"
                     width="1280" height="760" loading="lazy" alt="SubTrack dashboard screen">
            </div>
        </div>
    </div>
</section>

<!-- ============ Use cases with photos ============ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Who SubTrack is for</h2>
            <p>If your customers pay you every month or every year, SubTrack keeps the numbers straight.</p>
        </div>
        <div class="usecases">
            <div class="usecase">
                <img src="assets/img/photos/cafe-meeting.jpg" width="820" height="550" loading="lazy" alt="Two small business owners reviewing numbers on laptops in a café">
                <div class="body">
                    <div class="tag">Local services</div>
                    <h3>Gyms, salons &amp; tuition centres</h3>
                    <p>Manage monthly memberships, see who's due to renew this week and know your real monthly income.</p>
                </div>
            </div>
            <div class="usecase">
                <img src="assets/img/photos/office-desk.jpg" width="820" height="550" loading="lazy" alt="A founder working on a laptop at a desk in a bright office">
                <div class="body">
                    <div class="tag">Software &amp; digital</div>
                    <h3>Small SaaS &amp; online products</h3>
                    <p>Monthly and annual plans side by side, with MRR, ARR, ARPU and churn calculated automatically.</p>
                </div>
            </div>
            <div class="usecase">
                <img src="assets/img/photos/team-meeting.jpg" width="1400" height="1050" loading="lazy" alt="A team gathered around desks in an open-plan office discussing results">
                <div class="body">
                    <div class="tag">Growing teams</div>
                    <h3>Teams that report every month</h3>
                    <p>Pull a monthly revenue, new-subscription and cancellation summary for any month in seconds.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ Big quote ============ -->
<section class="section band">
    <div class="container">
        <div class="big-quote">
            <blockquote>"A spreadsheet can tell you who your customers are. It can't tell you, at a glance, whether your business is actually growing. That's the gap SubTrack fills."</blockquote>
            <div class="by">
                <span class="logo-mark" style="width:48px; height:48px; border-radius:50%; font-size:20px;">S</span>
                <div><strong>The idea behind SubTrack</strong><span>Why we built it</span></div>
            </div>
        </div>
    </div>
</section>

<!-- ============ How it works ============ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Up and running in four steps</h2>
            <p>No setup fees, no integrations to configure. Just sign up and start adding customers.</p>
        </div>
        <div class="steps">
            <div class="step"><div class="num">1</div><h4>Create your account</h4><p>Register with your business name and email. Your data is private to your account.</p></div>
            <div class="step"><div class="num">2</div><h4>Add plans &amp; customers</h4><p>Set up the monthly or annual plans you sell, then add the people who subscribe.</p></div>
            <div class="step"><div class="num">3</div><h4>Create subscriptions</h4><p>Link a customer to a plan. SubTrack sets the renewal date and logs the first payment.</p></div>
            <div class="step"><div class="num">4</div><h4>Watch revenue update</h4><p>Your dashboard, analytics and reports update instantly as you renew and cancel.</p></div>
        </div>
    </div>
</section>

<!-- ============ FAQ ============ -->
<section class="section band" id="faq">
    <div class="container">
        <div class="section-head"><h2>Frequently asked questions</h2></div>
        <div class="faq">
            <details open>
                <summary>Do I need to know how to code to use SubTrack?</summary>
                <p>No. Once it's running, SubTrack is a normal website — you register, sign in and use simple forms to manage customers, plans and subscriptions.</p>
            </details>
            <details>
                <summary>How are MRR and ARR calculated?</summary>
                <p>MRR adds up the price of every active subscription, converting annual plans into a monthly figure (price ÷ 12). ARR is simply MRR × 12. The <a href="about.php">About</a> page explains every metric.</p>
            </details>
            <details>
                <summary>Can more than one business use it?</summary>
                <p>Yes. Every account is independent — each owner registers separately and only ever sees their own customers, plans and numbers.</p>
            </details>
            <details>
                <summary>What happens when I renew or cancel a subscription?</summary>
                <p>Renewing moves the renewal date forward by one billing cycle and records a new payment. Cancelling marks the subscription inactive so it stops counting towards MRR.</p>
            </details>
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="section-sm">
    <div class="container">
        <div class="cta-band">
            <h2>Ready to see your revenue clearly?</h2>
            <p>Create a free account and start tracking customers, plans and recurring revenue in minutes.</p>
            <div class="actions">
                <a href="register.php" class="btn btn-light btn-lg">Create your account</a>
                <a href="contact.php" class="btn btn-line btn-lg">Talk to us</a>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    var shots = {
        dashboard: 'subtrack.local/dashboard',
        revenue: 'subtrack.local/revenue',
        subscriptions: 'subtrack.local/subscriptions'
    };
    var img = document.getElementById('showcaseImg');
    var url = document.getElementById('showcaseUrl');
    document.querySelectorAll('[data-shot-tab]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-shot-tab');
            document.querySelectorAll('[data-shot-tab]').forEach(function (b) {
                b.classList.toggle('is-active', b === btn);
                b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
            });
            img.src = 'assets/img/screens/' + key + '.png';
            img.alt = 'SubTrack ' + btn.textContent + ' screen';
            url.textContent = shots[key];
        });
    });
})();
</script>

<?php require __DIR__ . '/partials/site_footer.php'; ?>
