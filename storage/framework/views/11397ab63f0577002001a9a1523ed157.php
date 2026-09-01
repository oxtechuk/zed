<?php
    $logo = \App\Models\Setting::where('key', 'site_logo')->first()?->value;
    $siteName = \App\Models\Setting::where('key', 'site_name')->first()?->value;
    $siteNameText = is_array($siteName) ? ($siteName[app()->getLocale()] ?? ($siteName['ar'] ?? 'Zad Capital')) : ($siteName ?? 'Zad Capital');
    $currentUser = auth()->guard('employee')->user();
    $r = request()->route()?->getName() ?? '';

    // Helper: check if any route in a group is active
    $groupActive = function(array $prefixes) use ($r) {
        foreach ($prefixes as $prefix) {
            if (str_starts_with($r, $prefix)) return true;
        }
        return false;
    };
?>

<aside class="crm-sidebar">

    
    <button class="crm-sidebar-close" id="crmSidebarClose" aria-label="Close menu">
        <i class="bi bi-x-lg"></i>
    </button>

    
    <div class="crm-sidebar-logo">
        <?php if($logo): ?>
            <img src="<?php echo e(asset('storage/' . $logo)); ?>" alt="<?php echo e($siteNameText); ?>">
        <?php else: ?>
            <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                <span style="font-size:20px;font-weight:900;color:var(--crm-red);">GR</span>
                <span style="font-size:12px;font-weight:800;color:var(--crm-text);">Motors</span>
            </div>
        <?php endif; ?>
    </div>

    
    <nav class="crm-nav">

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-dashboard')): ?>
        <div class="crm-nav-section">
            <a href="<?php echo e(route('crm.dashboard')); ?>"
               class="crm-nav-link <?php echo e(str_starts_with($r,'crm.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-grid-1x2"></i>
                <span><?php echo e(__('الرئيسية')); ?></span>
            </a>
        </div>
        <?php endif; ?>

        
        <?php
            $catOpen = $groupActive(['crm.cars','crm.brands','crm.car-models','crm.brand-types','crm.car-categories','crm.car-types','crm.specifications','crm.features','crm.safety-features','crm.offers']);
            $canCatalog = $currentUser->hasAnyPermission(['manage-cars','manage-brands','manage-brand-types','manage-car-categories','manage-car-types','manage-specifications','manage-features','manage-safety-features','manage-offers']);
        ?>
        <?php if($canCatalog): ?>
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle <?php echo e($catOpen ? 'active' : ''); ?>"
                    onclick="toggleGroup('g-catalog')">
                <i class="bi bi-collection"></i>
                <span><?php echo e(__('الكتالوج')); ?></span>
                <i class="bi bi-chevron-<?php echo e($catOpen ? 'up' : 'down'); ?> crm-chevron"></i>
            </button>
            <ul id="g-catalog" class="crm-sub-list <?php echo e($catOpen ? 'open' : ''); ?>">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-cars')): ?>
                <li>
                    <a href="<?php echo e(route('crm.cars.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.cars') ? 'active' : ''); ?>">
                        <i class="bi bi-car-front"></i> <?php echo e(__('السيارات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-brands')): ?>
                <li>
                    <a href="<?php echo e(route('crm.brands.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.brands') ? 'active' : ''); ?>">
                        <i class="bi bi-bookmark-star"></i> <?php echo e(__('الماركات')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.car-models.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.car-models') ? 'active' : ''); ?>">
                        <i class="bi bi-list-nested"></i> <?php echo e(__('الموديلات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-brand-types')): ?>
                <li>
                    <a href="<?php echo e(route('crm.brand-types.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.brand-types') ? 'active' : ''); ?>">
                        <i class="bi bi-bookmarks"></i> <?php echo e(__('أنواع الماركات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-car-categories')): ?>
                <li>
                    <a href="<?php echo e(route('crm.car-categories.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.car-categories') ? 'active' : ''); ?>">
                        <i class="bi bi-folder2-open"></i> <?php echo e(__('التصنيفات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-car-types')): ?>
                <li>
                    <a href="<?php echo e(route('crm.car-types.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.car-types') ? 'active' : ''); ?>">
                        <i class="bi bi-tags"></i> <?php echo e(__('الأنواع')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-specifications')): ?>
                <li>
                    <a href="<?php echo e(route('crm.specifications.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.specifications') ? 'active' : ''); ?>">
                        <i class="bi bi-gear-wide-connected"></i> <?php echo e(__('المواصفات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-features')): ?>
                <li>
                    <a href="<?php echo e(route('crm.features.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.features') ? 'active' : ''); ?>">
                        <i class="bi bi-list-check"></i> <?php echo e(__('المميزات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-safety-features')): ?>
                <li>
                    <a href="<?php echo e(route('crm.safety-features.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.safety-features') ? 'active' : ''); ?>">
                        <i class="bi bi-shield-check"></i> <?php echo e(__('ميزات السلامة')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-offers')): ?>
                <li>
                    <a href="<?php echo e(route('crm.offers.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.offers') ? 'active' : ''); ?>">
                        <i class="bi bi-tags"></i> <?php echo e(__('العروض')); ?>

                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php
            $custOpen = $groupActive(['crm.leads','crm.calculator-leads','crm.contact-sources','crm.newsletter']);
            $canCustomers = $currentUser->hasAnyPermission(['manage-leads','manage-calculator-leads','manage-contact-sources','manage-newsletter']);
        ?>
        <?php if($canCustomers): ?>
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle <?php echo e($custOpen ? 'active' : ''); ?>"
                    onclick="toggleGroup('g-clients')">
                <i class="bi bi-people"></i>
                <span><?php echo e(__('إدارة العملاء')); ?></span>
                <i class="bi bi-chevron-<?php echo e($custOpen ? 'up' : 'down'); ?> crm-chevron"></i>
            </button>
            <ul id="g-clients" class="crm-sub-list <?php echo e($custOpen ? 'open' : ''); ?>">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-leads')): ?>
                <li>
                    <a href="<?php echo e(route('crm.leads.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.leads') ? 'active' : ''); ?>">
                        <i class="bi bi-person-lines-fill"></i> <?php echo e(__('العملاء')); ?>

                        <span class="nav-badge new-leads-badge ms-auto" style="display:none;">0</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-calculator-leads')): ?>
                <li>
                    <a href="<?php echo e(route('crm.calculator-leads.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.calculator-leads') ? 'active' : ''); ?>">
                        <i class="bi bi-person-badge"></i> <?php echo e(__('عملاء الحاسبة')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-contact-sources')): ?>
                <li>
                    <a href="<?php echo e(route('crm.contact-sources.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.contact-sources') ? 'active' : ''); ?>">
                        <i class="bi bi-broadcast"></i> <?php echo e(__('مصادر التواصل')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-newsletter')): ?>
                <li>
                    <a href="<?php echo e(route('crm.newsletter.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.newsletter') ? 'active' : ''); ?>">
                        <i class="bi bi-envelope-heart"></i> <?php echo e(__('مشتركو النشرة')); ?>

                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php
            $salesOpen = $groupActive(['crm.bookings','crm.tracking','crm.calculator.index']);
            $canSales = $currentUser->hasAnyPermission(['manage-bookings','manage-tracking','manage-calculator-settings']);
        ?>
        <?php if($canSales): ?>
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle <?php echo e($salesOpen ? 'active' : ''); ?>"
                    onclick="toggleGroup('g-sales')">
                <i class="bi bi-bag"></i>
                <span><?php echo e(__('المبيعات')); ?></span>
                <i class="bi bi-chevron-<?php echo e($salesOpen ? 'up' : 'down'); ?> crm-chevron"></i>
            </button>
            <ul id="g-sales" class="crm-sub-list <?php echo e($salesOpen ? 'open' : ''); ?>">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                <li>
                    <a href="<?php echo e(route('crm.bookings.index')); ?>"
                       class="crm-sub-link <?php echo e((request()->routeIs('crm.bookings.index') || request()->routeIs('crm.bookings.active') || request()->routeIs('crm.bookings.new') || request()->routeIs('crm.bookings.inprogress')) ? 'active' : ''); ?>">
                        <i class="bi bi-lightning-charge"></i> <?php echo e(__('الطلبات النشطة')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.bookings.pending')); ?>"
                       class="crm-sub-link <?php echo e(request()->routeIs('crm.bookings.pending') ? 'active' : ''); ?>">
                        <i class="bi bi-hourglass-split"></i> <?php echo e(__('طلبات قيد الانتظار')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.bookings.delivered')); ?>"
                       class="crm-sub-link <?php echo e((request()->routeIs('crm.bookings.delivered') || request()->routeIs('crm.bookings.completed')) ? 'active' : ''); ?>">
                        <i class="bi bi-check2-circle"></i> <?php echo e(__('طلبات تم التسليم')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.bookings.closed')); ?>"
                       class="crm-sub-link <?php echo e(request()->routeIs('crm.bookings.closed') ? 'active' : ''); ?>">
                        <i class="bi bi-folder-x"></i> <?php echo e(__('طلبات الإغلاق')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tracking')): ?>
                <li>
                    <a href="<?php echo e(route('crm.tracking.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.tracking') ? 'active' : ''); ?>">
                        <i class="bi bi-kanban"></i> <?php echo e(__('تتبع الحالات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-calculator-settings')): ?>
                <li>
                    <a href="<?php echo e(route('crm.calculator.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.calculator.index') ? 'active' : ''); ?>">
                        <i class="bi bi-calculator"></i> <?php echo e(__('إعدادات الحاسبة')); ?>

                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php
            $teamOpen = $groupActive(['crm.tasks','crm.employees','crm.roles']);
            $canTeam = $currentUser->hasAnyPermission(['manage-tasks','manage-employees','manage-roles']);
        ?>
        <?php if($canTeam): ?>
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle <?php echo e($teamOpen ? 'active' : ''); ?>"
                    onclick="toggleGroup('g-team')">
                <i class="bi bi-people-fill"></i>
                <span><?php echo e(__('الفرق والمهام')); ?></span>
                <i class="bi bi-chevron-<?php echo e($teamOpen ? 'up' : 'down'); ?> crm-chevron"></i>
            </button>
            <ul id="g-team" class="crm-sub-list <?php echo e($teamOpen ? 'open' : ''); ?>">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
                <li>
                    <a href="<?php echo e(route('crm.tasks.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.tasks') ? 'active' : ''); ?>">
                        <i class="bi bi-check2-square"></i> <?php echo e(__('المهام')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-employees')): ?>
                <li>
                    <a href="<?php echo e(route('crm.employees.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.employees') ? 'active' : ''); ?>">
                        <i class="bi bi-person-workspace"></i> <?php echo e(__('الموظفين')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-roles')): ?>
                <li>
                    <a href="<?php echo e(route('crm.roles.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.roles') ? 'active' : ''); ?>">
                        <i class="bi bi-shield-check"></i> <?php echo e(__('الأدوار والصلاحيات')); ?>

                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-reports')): ?>
        <?php
            $reportsOpen = $groupActive(['crm.reports.bookings', 'crm.reports.sources', 'crm.reports.monthly']);
        ?>
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle <?php echo e($reportsOpen ? 'active' : ''); ?>"
                    onclick="toggleGroup('g-reports')">
                <i class="bi bi-graph-up-arrow"></i>
                <span><?php echo e(__('التقارير والتحليلات')); ?></span>
                <i class="bi bi-chevron-<?php echo e($reportsOpen ? 'up' : 'down'); ?> crm-chevron"></i>
            </button>
            <ul id="g-reports" class="crm-sub-list <?php echo e($reportsOpen ? 'open' : ''); ?>">
                <li>
                    <a href="<?php echo e(route('crm.reports.sources')); ?>"
                       class="crm-sub-link <?php echo e($r === 'crm.reports.sources' ? 'active' : ''); ?>">
                        <i class="bi bi-bullseye text-primary"></i> <?php echo e(__('مصادر العملاء والحملات')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.reports.bookings')); ?>"
                       class="crm-sub-link <?php echo e($r === 'crm.reports.bookings' ? 'active' : ''); ?>">
                        <i class="bi bi-wallet2"></i> <?php echo e(__('التقرير الشامل والمبيعات')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.reports.monthly')); ?>"
                       class="crm-sub-link <?php echo e($r === 'crm.reports.monthly' ? 'active' : ''); ?>">
                        <i class="bi bi-calendar-range"></i> <?php echo e(__('التقرير الشهري')); ?>

                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php
            $contentOpen = $groupActive(['crm.blog','crm.blog-categories','crm.settings.designs','crm.settings.partners','crm.settings.testimonials','crm.settings.faqs','crm.translations','crm.settings.home-sections','crm.settings.hero-slides','crm.settings.promo-cards','crm.settings.promo-banners','crm.settings.finance-steps','crm.settings.budget-ranges']);
            $canContent = $currentUser->hasAnyPermission(['manage-blog','manage-designs','manage-partners','manage-testimonials','manage-faqs','manage-translations','manage-home-sections','manage-hero-slides','manage-promo-cards','manage-promo-banners','manage-finance-steps','manage-budget-ranges']);
        ?>
        <?php if($canContent): ?>
        <div class="crm-nav-section">
            <button class="crm-nav-link crm-group-toggle <?php echo e($contentOpen ? 'active' : ''); ?>"
                    onclick="toggleGroup('g-content')">
                <i class="bi bi-journal-richtext"></i>
                <span><?php echo e(__('المحتوى')); ?></span>
                <i class="bi bi-chevron-<?php echo e($contentOpen ? 'up' : 'down'); ?> crm-chevron"></i>
            </button>
            <ul id="g-content" class="crm-sub-list <?php echo e($contentOpen ? 'open' : ''); ?>">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-blog')): ?>
                <li>
                    <a href="<?php echo e(route('crm.blog.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.blog') && !str_contains($r,'crm.blog-categories') ? 'active' : ''); ?>">
                        <i class="bi bi-file-earmark-text"></i> <?php echo e(__('المدونة')); ?>

                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.blog-categories.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.blog-categories') ? 'active' : ''); ?>">
                        <i class="bi bi-tags"></i> <?php echo e(__('تصنيفات المقالات')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-designs')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.designs.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.designs') ? 'active' : ''); ?>">
                        <i class="bi bi-palette"></i> <?php echo e(__('معرض التصاميم')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-partners')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.partners.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.partners') ? 'active' : ''); ?>">
                        <i class="bi bi-hand-thumbs-up"></i> <?php echo e(__('شركاء النجاح')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-testimonials')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.testimonials.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.testimonials') ? 'active' : ''); ?>">
                        <i class="bi bi-chat-quote"></i> <?php echo e(__('آراء العملاء')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-faqs')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.faqs.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.faqs') ? 'active' : ''); ?>">
                        <i class="bi bi-question-circle"></i> <?php echo e(__('الأسئلة الشائعة')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-home-sections')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.home-sections.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.home-sections') ? 'active' : ''); ?>">
                        <i class="bi bi-layout-text-window"></i> <?php echo e(__('نصوص أقسام الرئيسية')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-hero-slides')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.hero-slides.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.hero-slides') ? 'active' : ''); ?>">
                        <i class="bi bi-images"></i> <?php echo e(__('شرائح الهيرو')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-promo-cards')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.promo-cards.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.promo-cards') ? 'active' : ''); ?>">
                        <i class="bi bi-grid-3x3-gap"></i> <?php echo e(__('البطاقات الترويجية')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-promo-banners')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.promo-banners.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.promo-banners') ? 'active' : ''); ?>">
                        <i class="bi bi-image-fill"></i> <?php echo e(__('البانرات الترويجية')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-finance-steps')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.finance-steps.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.finance-steps') ? 'active' : ''); ?>">
                        <i class="bi bi-list-ol"></i> <?php echo e(__('خطوات التمويل')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-budget-ranges')): ?>
                <li>
                    <a href="<?php echo e(route('crm.settings.budget-ranges.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.settings.budget-ranges') ? 'active' : ''); ?>">
                        <i class="bi bi-wallet2"></i> <?php echo e(__('نطاقات الميزانية')); ?>

                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-translations')): ?>
                <li>
                    <a href="<?php echo e(route('crm.translations.index')); ?>"
                       class="crm-sub-link <?php echo e(str_starts_with($r,'crm.translations') ? 'active' : ''); ?>">
                        <i class="bi bi-translate"></i> <?php echo e(__('إدارة الترجمة')); ?>

                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

    </nav>

    
    <div class="crm-sidebar-footer">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings-integrations')): ?>
        <a href="<?php echo e(route('crm.settings.integrations')); ?>"
           class="crm-nav-link <?php echo e(str_starts_with($r,'crm.settings.integrations') ? 'active' : ''); ?>">
            <i class="bi bi-plugin"></i>
            <span><?php echo e(__('الربط والإشعارات')); ?></span>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings')): ?>
        <a href="<?php echo e(route('crm.settings.general')); ?>"
           class="crm-nav-link <?php echo e(str_starts_with($r,'crm.settings.general') || (str_starts_with($r,'crm.settings') && !$contentOpen && !str_starts_with($r,'crm.settings.integrations')) ? 'active' : ''); ?>">
            <i class="bi bi-gear"></i>
            <span><?php echo e(__('الإعدادات')); ?></span>
        </a>
        <?php endif; ?>
        <form action="<?php echo e(route('crm.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="crm-nav-link w-100" style="background:none;border:none;cursor:pointer;color:#16254F;">
                <i class="bi bi-box-arrow-right"></i>
                <span><?php echo e(__('تسجيل الخروج')); ?></span>
            </button>
        </form>
    </div>

</aside>

<script>
function toggleGroup(id) {
    const list = document.getElementById(id);
    const btn  = list.previousElementSibling;
    const icon = btn.querySelector('.crm-chevron');
    const isOpen = list.classList.contains('open');
    // Close all others
    document.querySelectorAll('.crm-sub-list.open').forEach(el => {
        el.classList.remove('open');
        const b = el.previousElementSibling;
        if (b) { b.classList.remove('active'); const ic = b.querySelector('.crm-chevron'); if(ic) ic.className = 'bi bi-chevron-down crm-chevron'; }
    });
    if (!isOpen) {
        list.classList.add('open');
        btn.classList.add('active');
        if (icon) icon.className = 'bi bi-chevron-up crm-chevron';
    }
}
</script>
<?php /**PATH C:\wamp64\www\zed\resources\views/partials/crm-sidebar.blade.php ENDPATH**/ ?>