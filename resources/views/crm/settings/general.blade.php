@extends('partials.Layouts.crm-master')
@section('title', __('إعدادات الموقع') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="mb-4">
        <h4 class="mb-1 fw-bold">{{ __('إعدادات الموقع') }}</h4>
        <p class="text-muted mb-0 small">{{ __('تحكم في محتوى وإعدادات جميع صفحات الموقع من مكان واحد') }}</p>
    </div>

    @include('partials.settings-subnav')

    <form action="{{ route('crm.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4 align-items-start">

            {{-- ===== LEFT NAV ===== --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top:80px;">
                    <div class="card-body p-2">

                        <p class="nav-group-label">{{ __('عام') }}</p>
                        <nav class="nav flex-column gap-1 mb-1">
                            <button type="button" class="settings-nav-btn active" data-tab="basic"><i class="bi bi-info-circle"></i> {{ __('المعلومات الأساسية') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="appearance"><i class="bi bi-palette"></i> {{ __('الشعار والمظهر') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="contact"><i class="bi bi-telephone"></i> {{ __('التواصل والشبكات') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="loader"><i class="bi bi-hourglass-split"></i> {{ __('شاشة التحميل') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="popup"><i class="bi bi-megaphone"></i> {{ __('Popup ترويجي') }}</button>
                        </nav>

                        <p class="nav-group-label">{{ __('الصفحة الرئيسية') }}</p>
                        <nav class="nav flex-column gap-1 mb-1">
                            <button type="button" class="settings-nav-btn" data-tab="hero-slides"><i class="bi bi-images"></i> {{ __('شرائح الهيرو') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="bento"><i class="bi bi-grid-3x3-gap"></i> {{ __('البطاقات الترويجية') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="homepage-sections"><i class="bi bi-layout-text-window"></i> {{ __('نصوص الأقسام') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="finance-steps"><i class="bi bi-list-ol"></i> {{ __('خطوات التمويل') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="budget-ranges"><i class="bi bi-wallet2"></i> {{ __('نطاقات الميزانية') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="homepage-stats"><i class="bi bi-bar-chart-line"></i> {{ __('الإحصائيات') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="finance-stats"><i class="bi bi-bar-chart-line"></i> {{ __('إحصائيات التمويل') }}</button>
                        </nav>

                        <p class="nav-group-label">{{ __('صفحة السيارات') }}</p>
                        <nav class="nav flex-column gap-1 mb-1">
                            <button type="button" class="settings-nav-btn" data-tab="cars-hero"><i class="bi bi-image"></i> {{ __('هيرو صفحة السيارات') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="car-hero-ads"><i class="bi bi-car-front"></i> {{ __('إعلانات الهيرو') }}</button>
                        </nav>

                        <p class="nav-group-label">{{ __('صفحة العروض') }}</p>
                        <nav class="nav flex-column gap-1 mb-1">
                            <button type="button" class="settings-nav-btn" data-tab="offers-hero"><i class="bi bi-image"></i> {{ __('هيرو صفحة العروض') }}</button>
                        </nav>

                        <p class="nav-group-label">{{ __('صفحة من نحن') }}</p>
                        <nav class="nav flex-column gap-1 mb-1">
                            <button type="button" class="settings-nav-btn" data-tab="about-sections"><i class="bi bi-file-text"></i> {{ __('نصوص الأقسام') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="about-stats"><i class="bi bi-bar-chart-line"></i> {{ __('الإحصائيات') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="about-branches"><i class="bi bi-geo-alt"></i> {{ __('فروع التواجد') }}</button>
                        </nav>

                        <p class="nav-group-label">{{ __('صفحة الحجز') }}</p>
                        <nav class="nav flex-column gap-1">
                            <button type="button" class="settings-nav-btn" data-tab="booking-hero"><i class="bi bi-image"></i> {{ __('هيرو الحجز') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="booking-steps"><i class="bi bi-list-ol"></i> {{ __('خطوات الحجز') }}</button>
                            <button type="button" class="settings-nav-btn" data-tab="booking-sections"><i class="bi bi-layout-text-window"></i> {{ __('نصوص الأقسام') }}</button>
                        </nav>

                    </div>
                </div>
            </div>

            {{-- ===== CONTENT ===== --}}
            <div class="col-lg-6">
                <div id="settingsTabContent">

                    {{-- =============================== --}}
                    {{-- TAB: المعلومات الأساسية        --}}
                    {{-- =============================== --}}
                    <div class="settings-pane active" id="tab-basic">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('المعلومات الأساسية') }}</h6>
                                <p class="text-muted small mb-0">{{ __('اسم الموقع ونص التذييل') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('اسم الموقع — عربي') }}</label>
                                        <input type="text" name="site_name[ar]" class="form-control bg-light border-0" value="{{ $settings['site_name']['ar'] ?? '' }}" placeholder="مثال: zed Capital">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('اسم الموقع — إنجليزي') }}</label>
                                        <input type="text" name="site_name[en]" class="form-control bg-light border-0" value="{{ $settings['site_name']['en'] ?? '' }}" placeholder="e.g.: Knooz Car">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted">{{ __('نص التذييل (Footer Text)') }}</label>
                                        <textarea name="footer_text" class="form-control bg-light border-0" rows="3" placeholder="{{ __('النص الذي يظهر في أسفل جميع الصفحات...') }}">{{ (is_array($settings['footer_text'])? $settings['footer_text'][app()->getLocale()] :$settings['footer_text']) ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                            <div>
                                                <p class="fw-semibold mb-0 small">{{ __('التوزيع التلقائي للطلبات') }}</p>
                                                <p class="text-muted small mb-0">{{ __('توزيع طلبات الحجز تلقائياً (Round-Robin) على موظفي المبيعات') }}</p>
                                            </div>
                                            <div class="form-check form-switch fs-5 mb-0">
                                                <input type="hidden" name="auto_assign_bookings" value="0">
                                                <input class="form-check-input" type="checkbox" name="auto_assign_bookings" value="1" {{ ($settings['auto_assign_bookings'] ?? '0') == '1' ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: الشعار والمظهر            --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-appearance">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('الشعار والمظهر') }}</h6>
                                <p class="text-muted small mb-0">{{ __('اللوجو والأيقونة وخلفية الصفحات الداخلية') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('شعار الهيدر (Header Logo)') }}</label>
                                        <div class="upload-preview rounded-3 mb-2">
                                            @if(isset($settings['site_logo']))
                                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Header Logo" class="img-fluid" style="max-height:60px;">
                                            @else
                                                <i class="bi bi-image fs-2 opacity-25"></i>
                                            @endif
                                        </div>
                                        <input type="file" name="site_logo" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('شعار الفوتر (Footer Logo)') }}</label>
                                        <div class="upload-preview rounded-3 mb-2">
                                            @if(isset($settings['footer_logo']))
                                                <img src="{{ asset('storage/' . $settings['footer_logo']) }}" alt="Footer Logo" class="img-fluid" style="max-height:60px;">
                                            @else
                                                <i class="bi bi-image fs-2 opacity-25"></i>
                                            @endif
                                        </div>
                                        <input type="file" name="footer_logo" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('أيقونة (Favicon)') }}</label>
                                        <div class="upload-preview rounded-3 mb-2">
                                            @if(isset($settings['site_favicon']))
                                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" width="32">
                                            @else
                                                <i class="bi bi-app-indicator fs-2 opacity-25"></i>
                                            @endif
                                        </div>
                                        <input type="file" name="site_favicon" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('خلفية الصفحات (Breadcrumb)') }}</label>
                                        <div class="upload-preview rounded-3 mb-2 bg-dark">
                                            @if(isset($settings['breadcrumb_bg']))
                                                <img src="{{ asset('storage/' . $settings['breadcrumb_bg']) }}" class="img-fluid w-100 object-fit-cover rounded-3" style="max-height:80px;">
                                            @else
                                                <i class="bi bi-layout-text-window-reverse fs-2 text-white opacity-25"></i>
                                            @endif
                                        </div>
                                        <input type="file" name="breadcrumb_bg" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3 bg-info-subtle rounded-3 small text-info">
                                            <i class="bi bi-info-circle-fill me-1"></i>
                                            {{ __('خلفية الصفحات تظهر في صفحات: من نحن، العروض، المدونة، وغيرها.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: التواصل والشبكات          --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-contact">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('بيانات التواصل') }}</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('البريد الإلكتروني') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="contact_email" class="form-control bg-light border-0" value="{{ $settings['contact_email'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('رقم الهاتف') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                                            <input type="text" name="contact_phone" class="form-control bg-light border-0" value="{{ $settings['contact_phone'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('رقم الواتساب') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-whatsapp"></i></span>
                                            <input type="text" name="contact_whatsapp" class="form-control bg-light border-0" value="{{ $settings['contact_whatsapp'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" name="contact_address" class="form-control bg-light border-0" value="{{ (is_array($settings['contact_address']) ? $settings['contact_address'][app()->getLocale()] :$settings['contact_address']) ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-3 border-top pt-3">
                                    <h6 class="fw-bold mb-0 small text-dark"><i class="bi bi-share me-2"></i>{{ __('روابط التواصل الاجتماعي') }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addSocialRow()">
                                        <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة') }}
                                    </button>
                                </div>

                                <div id="social-container" class="d-flex flex-column gap-2">
                                    @foreach($socialMedia as $idx => $social)
                                    <div class="social-row d-flex align-items-center gap-2 p-3 bg-light rounded-3" id="social-row-{{ $idx }}">
                                        <input type="text" name="social_icon[]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('bi-facebook') }}" value="{{ $social['icon'] ?? '' }}" style="max-width:150px;">
                                        <input type="color" name="social_color[]" class="form-control form-control-color border-0 bg-white shadow-none p-1" value="{{ $social['color'] ?? '#333333' }}" style="width:40px;height:38px;">
                                        <input type="url" name="social_link[]" class="form-control border-0 bg-white shadow-none text-start flex-grow-1" dir="ltr" placeholder="https://..." value="{{ $social['link'] ?? '' }}">
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-1 lh-1" onclick="removeSocialRow({{ $idx }})"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="no-social-msg" class="text-center py-4 bg-light rounded-3 {{ count($socialMedia) > 0 ? 'd-none' : '' }}">
                                    <span class="text-muted small">{{ __('لا توجد حسابات بعد') }}</span>
                                </div>
                                <p class="text-muted small mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>{{ __('أيقونات Bootstrap Icons:') }} <code>bi-facebook</code>, <code>bi-instagram</code>, <code>bi-tiktok</code>…</p>

                                <div class="d-flex align-items-center justify-content-between mb-3 border-top pt-3 mt-4">
                                    <h6 class="fw-bold mb-0 small text-dark"><i class="bi bi-link-45deg me-2"></i>{{ __('روابط الفوتر السريعة') }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addFooterLinkRow('quick')">
                                        <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة') }}
                                    </button>
                                </div>
                                <div id="footer-quick-links-container" class="d-flex flex-column gap-2">
                                    @foreach($footerQuickLinks as $idx => $link)
                                    <div class="footer-link-row-quick d-flex align-items-center gap-2 p-3 bg-light rounded-3" id="footer-quick-link-row-{{ $idx }}">
                                        <input type="text" name="footer_quick_links[{{ $idx }}][title][ar]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('العنوان (عربي)') }}" value="{{ $link['title']['ar'] ?? '' }}">
                                        <input type="text" name="footer_quick_links[{{ $idx }}][title][en]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('العنوان (EN)') }}" value="{{ $link['title']['en'] ?? '' }}">
                                        <input type="text" name="footer_quick_links[{{ $idx }}][url]" class="form-control border-0 bg-white shadow-none text-start flex-grow-1" dir="ltr" placeholder="/about" value="{{ $link['url'] ?? '' }}">
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-1 lh-1" onclick="removeFooterLinkRow(this)"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-3 border-top pt-3 mt-4">
                                    <h6 class="fw-bold mb-0 small text-dark"><i class="bi bi-link-45deg me-2"></i>{{ __('روابط الخدمات في الفوتر') }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addFooterLinkRow('service')">
                                        <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة') }}
                                    </button>
                                </div>
                                <div id="footer-service-links-container" class="d-flex flex-column gap-2">
                                    @foreach($footerServiceLinks as $idx => $link)
                                    <div class="footer-link-row-service d-flex align-items-center gap-2 p-3 bg-light rounded-3" id="footer-service-link-row-{{ $idx }}">
                                        <input type="text" name="footer_service_links[{{ $idx }}][title][ar]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('العنوان (عربي)') }}" value="{{ $link['title']['ar'] ?? '' }}">
                                        <input type="text" name="footer_service_links[{{ $idx }}][title][en]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('العنوان (EN)') }}" value="{{ $link['title']['en'] ?? '' }}">
                                        <input type="text" name="footer_service_links[{{ $idx }}][url]" class="form-control border-0 bg-white shadow-none text-start flex-grow-1" dir="ltr" placeholder="/calculator" value="{{ $link['url'] ?? '' }}">
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-1 lh-1" onclick="removeFooterLinkRow(this)"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: شاشة التحميل              --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-loader">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('شاشة التحميل') }}</h6>
                                <p class="text-muted small mb-0">{{ __('الشاشة التي تظهر أثناء تحميل الصفحة') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 mb-4">
                                    <div>
                                        <p class="fw-semibold mb-0 small">{{ __('تفعيل شاشة التحميل') }}</p>
                                        <p class="text-muted small mb-0">{{ __('تظهر قبل اكتمال تحميل الصفحة') }}</p>
                                    </div>
                                    <div class="form-check form-switch fs-5 mb-0">
                                        <input class="form-check-input" type="checkbox" name="page_loader_enabled" value="1" {{ ($settings['page_loader_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('صورة / GIF التحميل') }}</label>
                                    <div class="upload-preview rounded-3 mb-2" style="background:#0f0f11;">
                                        @if(!empty($settings['page_loader_image']))
                                            <img src="{{ asset('storage/' . $settings['page_loader_image']) }}" style="max-height:80px;object-fit:contain;">
                                        @else
                                            <i class="bi bi-image fs-2 text-white opacity-25"></i>
                                        @endif
                                    </div>
                                    <input type="file" name="page_loader_image" class="form-control bg-light border-0" accept="image/*">
                                    <p class="text-muted small mt-1 mb-0">{{ __('PNG أو GIF متحرك') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: Popup ترويجي              --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-popup">
                        @php
                            $promoPopup = isset($settings['promo_popup'])
                                ? (is_array($settings['promo_popup']) ? $settings['promo_popup'] : (json_decode($settings['promo_popup'], true) ?: []))
                                : [];
                        @endphp
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('Popup ترويجي') }}</h6>
                                <p class="text-muted small mb-0">{{ __('يظهر للزوار بعد 5 دقائق من التصفح') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 mb-4">
                                    <p class="fw-semibold mb-0 small">{{ __('تفعيل الـ Popup') }}</p>
                                    <div class="form-check form-switch fs-5 mb-0">
                                        <input type="hidden" name="popup_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" name="popup_enabled" value="1" {{ ($promoPopup['enabled'] ?? false) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('صورة الـ Popup') }}</label>
                                        <div class="upload-preview rounded-3 mb-2" style="min-height:140px;">
                                            @if(!empty($promoPopup['image']))
                                                <img src="{{ asset('storage/' . $promoPopup['image']) }}" class="w-100 object-fit-cover rounded-3" style="max-height:140px;">
                                            @else
                                                <i class="bi bi-image fs-2 opacity-25"></i>
                                            @endif
                                        </div>
                                        <input type="file" name="popup_image" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-muted">{{ __('العنوان') }}</label>
                                                <input type="text" name="popup_title" class="form-control bg-light border-0" value="{{ $promoPopup['title'] ?? '' }}" placeholder="{{ __('عروض مميزة لهذا الشهر!') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-muted">{{ __('نص الوصف') }}</label>
                                                <textarea name="popup_text" class="form-control bg-light border-0" rows="3" placeholder="{{ __('نص قصير جذاب...') }}">{{ $promoPopup['text'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-muted">{{ __('رابط الزر') }}</label>
                                                <input type="text" name="popup_link" class="form-control bg-light border-0 text-start" dir="ltr" value="{{ $promoPopup['link'] ?? '' }}" placeholder="https://...">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-muted">{{ __('نص الزر') }}</label>
                                                <input type="text" name="popup_button_text" class="form-control bg-light border-0" value="{{ $promoPopup['button_text'] ?? __('تصفح العروض') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: شرائح الهيرو  (-> شاشة مخصصة) --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-hero-slides">
                        @include('partials.settings-manage-link', [
                            'icon' => 'bi-images',
                            'title' => __('شرائح الهيرو'),
                            'description' => __('الهيرو الرئيسي وشرائحه أصبحا يُداران من شاشة مخصصة (عنوان، وصف، صورة ديسكتوب/موبايل، زر، شارة، ترتيب لكل شريحة).'),
                            'route' => 'crm.settings.hero-slides.index',
                            'permission' => 'manage-hero-slides',
                            'count' => \App\Models\HeroSlide::count(),
                            'countLabel' => __('شريحة'),
                        ])
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: نصوص أقسام الرئيسية (-> شاشة مخصصة) --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-homepage-sections">
                        @include('partials.settings-manage-link', [
                            'icon' => 'bi-layout-text-window',
                            'title' => __('نصوص أقسام الرئيسية'),
                            'description' => __('عنوان ووصف وشارة وزر كل قسم من أقسام الصفحة الرئيسية (البحث، الماركات، السيارات المميزة، البانر الترويجي، أحدث السيارات، الميزانية، التمويل، الفوتر) — بما فيها القسم المميز القديم الذي أصبح جزءاً من البانر الترويجي.'),
                            'route' => 'crm.settings.home-sections.index',
                            'permission' => 'manage-home-sections',
                            'count' => \App\Models\HomeSection::count(),
                            'countLabel' => __('قسم'),
                        ])
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: خطوات التمويل (جديد)        --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-finance-steps">
                        @include('partials.settings-manage-link', [
                            'icon' => 'bi-list-ol',
                            'title' => __('خطوات التمويل'),
                            'description' => __('خطوات قسم "كيف يعمل التمويل" في الصفحة الرئيسية — رقم وعنوان ووصف وأيقونة لكل خطوة.'),
                            'route' => 'crm.settings.finance-steps.index',
                            'permission' => 'manage-finance-steps',
                            'count' => \App\Models\FinanceStep::count(),
                            'countLabel' => __('خطوة'),
                        ])
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: نطاقات الميزانية (جديد)     --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-budget-ranges">
                        @include('partials.settings-manage-link', [
                            'icon' => 'bi-wallet2',
                            'title' => __('نطاقات الميزانية'),
                            'description' => __('التبويبات السعرية في قسم "سيارات حسب ميزانيتك" (مثال: أقل من 300 ألف، 300-500 ألف...).'),
                            'route' => 'crm.settings.budget-ranges.index',
                            'permission' => 'manage-budget-ranges',
                            'count' => \App\Models\BudgetRange::count(),
                            'countLabel' => __('نطاق'),
                        ])
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: إحصائيات الرئيسية         --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-homepage-stats">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('إحصائيات الصفحة الرئيسية') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('الأرقام التي تظهر في قسم الإنجازات') }}</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addStatRow('stats', 'stat_value', 'stat_label')">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة') }}
                                </button>
                            </div>
                            <div class="card-body p-4">
                                <div id="stats-container" class="d-flex flex-column gap-2">
                                    @foreach($homepageStats as $idx => $stat)
                                    <div class="stat-row d-flex align-items-center gap-2 p-3 bg-light rounded-3" id="stat-row-{{ $idx }}">
                                        <input type="text" name="stat_value[]" class="form-control border-0 bg-white fw-bold text-center" value="{{ $stat['value'] ?? '' }}" placeholder="+500" style="max-width:90px;">
                                        <input type="text" name="stat_label[]" class="form-control border-0 bg-white flex-grow-1" value="{{ $stat['label'] ?? '' }}" placeholder="{{ __('التسمية') }}">
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeStatRow({{ $idx }}, 'stat-row')"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="no-stats-msg" class="text-center py-4 bg-light rounded-3 {{ count($homepageStats) > 0 ? 'd-none' : '' }}">
                                    <span class="text-muted small">{{ __('لا توجد إحصائيات بعد') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: إحصائيات التمويل           --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-finance-stats">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('إحصائيات قسم التمويل') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('الأرقام التي تظهر في قسم حلول التمويل') }}</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addFinanceStatRow()">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة') }}
                                </button>
                            </div>
                            <div class="card-body p-4">
                                <div id="finance-stats-container" class="d-flex flex-column gap-2">
                                    @foreach($financeStats as $idx => $stat)
                                    <div class="finance-stat-row d-flex align-items-center gap-2 p-3 bg-light rounded-3" id="finance-stat-row-{{ $idx }}">
                                        <input type="text" name="finance_stat_value[]" class="form-control border-0 bg-white fw-bold text-center" value="{{ $stat['value'] ?? '' }}" placeholder="500" style="max-width:90px;">
                                        <input type="text" name="finance_stat_label[]" class="form-control border-0 bg-white flex-grow-1" value="{{ $stat['label'] ?? '' }}" placeholder="{{ __('التسمية') }}">
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeFinanceStatRow({{ $idx }})"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="no-finance-stats-msg" class="text-center py-4 bg-light rounded-3 {{ count($financeStats) > 0 ? 'd-none' : '' }}">
                                    <span class="text-muted small">{{ __('لا توجد إحصائيات بعد') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: معرض Bento                 --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-bento">
                        <div class="d-flex flex-column gap-4">
                            @include('partials.settings-manage-link', [
                                'icon' => 'bi-grid-3x3-gap',
                                'title' => __('البطاقات الترويجية'),
                                'description' => __('البطاقات التي كانت تُعرض هنا كسيارات (Bento) استُبدلت ببطاقات CMS حقيقية (عنوان، وصف، صورة، زر، شارة) لأنها ليست سيارات فعلية حسب تصميم الصفحة الرئيسية.'),
                                'route' => 'crm.settings.promo-cards.index',
                                'permission' => 'manage-promo-cards',
                                'count' => \App\Models\PromoCard::count(),
                                'countLabel' => __('بطاقة'),
                            ])

                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ __('المعرض الرئيسي') }}</h6>
                                        <p class="text-muted small mb-0">{{ __('صور صفحة من نحن') }}</p>
                                    </div>
                                    <span class="badge bg-light text-muted rounded-pill px-3">{{ count($settings['main_gallery'] ?? []) }} {{ __('صورة') }}</span>
                                </div>
                                <div class="card-body p-4">
                                    <input type="file" name="main_gallery[]" class="form-control bg-light border-0 mb-4" multiple accept="image/*">
                                    @if(!empty($settings['main_gallery']))
                                        @php $gallery = is_array($settings['main_gallery']) ? $settings['main_gallery'] : (json_decode($settings['main_gallery'], true) ?: []); @endphp
                                        <div class="row g-2">
                                            @foreach($gallery as $img)
                                            <div class="col-4 col-md-3 position-relative">
                                                <div class="rounded-3 overflow-hidden" style="height:100px;">
                                                    <img src="{{ asset('storage/' . $img) }}" class="w-100 h-100 object-fit-cover">
                                                </div>
                                                <button type="submit" name="delete_gallery_image" value="{{ $img }}" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1 lh-1 p-1" onclick="return confirm('{{ __('حذف هذه الصورة؟') }}')" style="width:24px;height:24px;font-size:11px;"><i class="bi bi-x"></i></button>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 bg-light rounded-3">
                                            <span class="text-muted small">{{ __('لا توجد صور بعد') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: هيرو صفحة السيارات         --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-cars-hero">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('هيرو صفحة السيارات') }}</h6>
                                <p class="text-muted small mb-0">{{ __('العنوان والصورة التي تظهر في أعلى صفحة كل السيارات') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان — عربي') }}</label>
                                        <input type="text" name="store_hero[title][ar]" class="form-control bg-light border-0" value="{{ $carsHero['title']['ar'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان — إنجليزي') }}</label>
                                        <input type="text" name="store_hero[title][en]" class="form-control bg-light border-0" value="{{ $carsHero['title']['en'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('الوصف — عربي') }}</label>
                                        <textarea name="store_hero[subtitle][ar]" rows="3" class="form-control bg-light border-0">{{ $carsHero['subtitle']['ar'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('الوصف — إنجليزي') }}</label>
                                        <textarea name="store_hero[subtitle][en]" rows="3" class="form-control bg-light border-0">{{ $carsHero['subtitle']['en'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('صورة الهيرو') }}</label>
                                        @if(!empty($carsHero['image']))
                                            <div class="mb-2 rounded-3 overflow-hidden bg-light" style="max-height:120px;">
                                                <img src="{{ asset('storage/' . $carsHero['image']) }}" class="img-fluid w-100 object-fit-cover" style="max-height:120px;">
                                            </div>
                                            <input type="hidden" name="store_hero[image]" value="{{ $carsHero['image'] }}">
                                        @endif
                                        <input type="file" name="cars_hero_image" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: إعلانات هيرو السيارات      --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-car-hero-ads">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('إعلانات هيرو صفحة السيارات') }}</h6>
                                <p class="text-muted small mb-0">{{ __('البانرات الإعلانية التي تظهر في أعلى صفحة السيارات') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    @foreach([1, 2] as $adNum)
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <p class="fw-semibold small mb-3">{{ __('إعلان') }} {{ $adNum }}</p>
                                            <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('الصورة') }}</label>
                                            <div class="upload-preview rounded-3 mb-2" style="min-height:100px;">
                                                @if(!empty($settings['hero_ad_' . $adNum . '_image']))
                                                    <img src="{{ asset('storage/' . $settings['hero_ad_' . $adNum . '_image']) }}" class="w-100 object-fit-cover rounded-3" style="max-height:100px;">
                                                @else
                                                    <i class="bi bi-image fs-2 opacity-25"></i>
                                                @endif
                                            </div>
                                            <input type="file" name="hero_ad_{{ $adNum }}_image" class="form-control bg-white border-0 mb-2 form-control-sm" accept="image/*">
                                            <label class="form-label fw-semibold small text-muted">{{ __('الرابط') }}</label>
                                            <input type="url" name="hero_ad_{{ $adNum }}_link" class="form-control bg-white border-0 text-start form-control-sm" dir="ltr" value="{{ $settings['hero_ad_' . $adNum . '_link'] ?? '' }}" placeholder="https://...">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: هيرو صفحة العروض           --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-offers-hero">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('هيرو صفحة العروض') }}</h6>
                                <p class="text-muted small mb-0">{{ __('العنوان والصورة التي تظهر في أعلى صفحة العروض') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان — عربي') }}</label>
                                        <input type="text" name="store_offers_hero[title][ar]" class="form-control bg-light border-0" value="{{ $offersHero['title']['ar'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان — إنجليزي') }}</label>
                                        <input type="text" name="store_offers_hero[title][en]" class="form-control bg-light border-0" value="{{ $offersHero['title']['en'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('الوصف — عربي') }}</label>
                                        <textarea name="store_offers_hero[subtitle][ar]" rows="3" class="form-control bg-light border-0">{{ $offersHero['subtitle']['ar'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('الوصف — إنجليزي') }}</label>
                                        <textarea name="store_offers_hero[subtitle][en]" rows="3" class="form-control bg-light border-0">{{ $offersHero['subtitle']['en'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('صورة الهيرو') }}</label>
                                        @if(!empty($offersHero['image']))
                                            <div class="mb-2 rounded-3 overflow-hidden bg-light" style="max-height:120px;">
                                                <img src="{{ asset('storage/' . $offersHero['image']) }}" class="img-fluid w-100 object-fit-cover" style="max-height:120px;">
                                            </div>
                                            <input type="hidden" name="store_offers_hero[image]" value="{{ $offersHero['image'] }}">
                                        @endif
                                        <input type="file" name="offers_hero_image" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: أقسام صفحة من نحن         --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-about-sections">
                        @php $asec = $aboutSections; @endphp
                        <div class="d-flex flex-column gap-3">

                            @php
                                $aboutSectionsDef = [
                                    [
                                        'id' => 'hero', 'icon' => 'bi-house', 'label' => __('قسم الهيرو'),
                                        'fields' => ['badge','title','subtitle'],
                                        'fieldLabels' => ['badge' => __('الشارة'), 'title' => __('العنوان'), 'subtitle' => __('الوصف')],
                                        'textarea' => ['subtitle'],
                                    ],
                                    [
                                        'id' => 'story', 'icon' => 'bi-book', 'label' => __('قصة الشركة'),
                                        'fields' => ['title','content','mission_title','mission_text','vision_title','vision_text','values_title','values_text'],
                                        'fieldLabels' => ['title' => __('عنوان القسم'), 'content' => __('النص'), 'mission_title' => __('عنوان الرسالة'), 'mission_text' => __('نص الرسالة'), 'vision_title' => __('عنوان الرؤية'), 'vision_text' => __('نص الرؤية'), 'values_title' => __('عنوان القيم'), 'values_text' => __('نص القيم')],
                                        'textarea' => ['content','mission_text','vision_text','values_text'],
                                    ],
                                    [
                                        'id' => 'partners', 'icon' => 'bi-briefcase', 'label' => __('قسم الشركاء'),
                                        'fields' => ['badge','title','subtitle'],
                                        'fieldLabels' => ['badge' => __('الشارة'), 'title' => __('العنوان'), 'subtitle' => __('الوصف')],
                                        'textarea' => ['subtitle'],
                                    ],
                                    [
                                        'id' => 'dealer', 'icon' => 'bi-handshake', 'label' => __('قسم الموزعين'),
                                        'fields' => ['title','description','partner_button_text','contact_button_text'],
                                        'fieldLabels' => ['title' => __('العنوان'), 'description' => __('الوصف'), 'partner_button_text' => __('نص زر الشراكة'), 'contact_button_text' => __('نص زر التواصل')],
                                        'textarea' => ['description'],
                                        'plain_fields' => ['partner_button_link' => __('رابط زر الشراكة')],
                                    ],
                                    [
                                        'id' => 'locations', 'icon' => 'bi-geo-alt', 'label' => __('قسم المواقع'),
                                        'fields' => ['title'],
                                        'fieldLabels' => ['title' => __('العنوان')],
                                        'textarea' => [],
                                    ],
                                    [
                                        'id' => 'testimonials', 'icon' => 'bi-chat-quote', 'label' => __('قسم التقييمات'),
                                        'fields' => ['badge','title','rating_text'],
                                        'fieldLabels' => ['badge' => __('الشارة'), 'title' => __('العنوان'), 'rating_text' => __('نص التقييم')],
                                        'textarea' => [],
                                    ],
                                ];
                            @endphp

                            @foreach($aboutSectionsDef as $aIdx => $aDef)
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <button type="button" class="btn text-start p-4 d-flex align-items-center gap-3 collapsed-section-toggle border-0 bg-white rounded-4" onclick="toggleSection('a-{{ $aDef['id'] }}', this)">
                                    <i class="bi {{ $aDef['icon'] }} text-danger"></i>
                                    <span class="fw-semibold">{{ $aDef['label'] }}</span>
                                    <i class="bi bi-chevron-down ms-auto text-muted small toggle-chevron"></i>
                                </button>
                                <div class="section-body {{ $aIdx === 0 ? '' : 'd-none' }} border-top" id="a-{{ $aDef['id'] }}">
                                    <div class="p-4">
                                        <div class="row g-3">
                                            @foreach($aDef['fields'] as $aField)
                                            @php $isTA = in_array($aField, $aDef['textarea']); @endphp
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-muted">{{ $aDef['fieldLabels'][$aField] }} — {{ __('عربي') }}</label>
                                                @if($isTA)
                                                    <textarea name="about_sections[{{ $aDef['id'] }}][{{ $aField }}][ar]" class="form-control bg-light border-0" rows="3">{{ $asec[$aDef['id']][$aField]['ar'] ?? '' }}</textarea>
                                                @else
                                                    <input type="text" name="about_sections[{{ $aDef['id'] }}][{{ $aField }}][ar]" class="form-control bg-light border-0" value="{{ $asec[$aDef['id']][$aField]['ar'] ?? '' }}">
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-muted">{{ $aDef['fieldLabels'][$aField] }} — {{ __('إنجليزي') }}</label>
                                                @if($isTA)
                                                    <textarea name="about_sections[{{ $aDef['id'] }}][{{ $aField }}][en]" class="form-control bg-light border-0" rows="3">{{ $asec[$aDef['id']][$aField]['en'] ?? '' }}</textarea>
                                                @else
                                                    <input type="text" name="about_sections[{{ $aDef['id'] }}][{{ $aField }}][en]" class="form-control bg-light border-0" value="{{ $asec[$aDef['id']][$aField]['en'] ?? '' }}">
                                                @endif
                                            </div>
                                            @endforeach

                                            @if(!empty($aDef['plain_fields']))
                                                @foreach($aDef['plain_fields'] as $pKey => $pLabel)
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold small text-muted">{{ $pLabel }}</label>
                                                    <input type="text" name="about_sections[{{ $aDef['id'] }}][{{ $pKey }}]" class="form-control bg-light border-0 text-start" dir="ltr" value="{{ $asec[$aDef['id']][$pKey] ?? '' }}" placeholder="https://...">
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: إحصائيات من نحن           --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-about-stats">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('إحصائيات صفحة من نحن') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('الأرقام التي تظهر في هيرو صفحة من نحن') }}</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addAboutStatRow()">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة') }}
                                </button>
                            </div>
                            <div class="card-body p-4">
                                <div id="about-stats-container" class="d-flex flex-column gap-2">
                                    @foreach($aboutStats as $idx => $stat)
                                    <div class="about-stat-row d-flex align-items-center gap-2 p-3 bg-light rounded-3" id="about-stat-row-{{ $idx }}">
                                        <input type="text" name="about_stat_value[]" class="form-control border-0 bg-white fw-bold text-center" value="{{ $stat['value'] ?? '' }}" placeholder="+500" style="max-width:90px;">
                                        <input type="text" name="about_stat_label[]" class="form-control border-0 bg-white flex-grow-1" value="{{ $stat['label'] ?? '' }}" placeholder="{{ __('التسمية') }}">
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeAboutStatRow({{ $idx }})"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="no-about-stats-msg" class="text-center py-4 bg-light rounded-3 {{ count($aboutStats) > 0 ? 'd-none' : '' }}">
                                    <span class="text-muted small">{{ __('لا توجد إحصائيات بعد') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: فروع التواجد               --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-about-branches">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('فروع التواجد') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('مواقع الفروع التي تظهر في صفحة من نحن') }}</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addBranchRow()">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة فرع') }}
                                </button>
                            </div>
                            <div class="card-body p-4">
                                <div id="branches-container" class="d-flex flex-column gap-3">
                                    @foreach($aboutBranches as $idx => $branch)
                                    <div class="branch-row card border border-light-subtle rounded-3" id="branch-row-{{ $idx }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold small text-muted">{{ __('فرع') }} {{ $idx + 1 }}</span>
                                                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeBranchRow({{ $idx }})"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('المدينة') }}</label>
                                                    <input type="text" name="branch_city[]" class="form-control bg-light border-0 form-control-sm" value="{{ $branch['city'] ?? '' }}" placeholder="{{ __('الرياض') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('اسم الفرع') }}</label>
                                                    <input type="text" name="branch_name[]" class="form-control bg-light border-0 form-control-sm" value="{{ $branch['name'] ?? '' }}" placeholder="{{ __('فرع الرياض') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('العنوان التفصيلي') }}</label>
                                                    <input type="text" name="branch_address[]" class="form-control bg-light border-0 form-control-sm" value="{{ $branch['address'] ?? '' }}" placeholder="{{ __('طريق الملك فهد، مجمع...') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('رقم الهاتف') }}</label>
                                                    <input type="text" name="branch_phone[]" class="form-control bg-light border-0 form-control-sm" value="{{ $branch['phone'] ?? '' }}" placeholder="+966 5X XXX XXXX">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('أوقات العمل') }}</label>
                                                    <input type="text" name="branch_hours[]" class="form-control bg-light border-0 form-control-sm" value="{{ $branch['working_hours'] ?? '' }}" placeholder="{{ __('السبت - الخميس، 9 صباحاً - 8 مساءً') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('رابط الخريطة') }}</label>
                                                    <input type="url" name="branch_map_link[]" class="form-control bg-light border-0 form-control-sm text-start" dir="ltr" value="{{ $branch['map_link'] ?? '' }}" placeholder="https://maps.google.com/...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="no-branches-msg" class="text-center py-5 bg-light rounded-3 {{ count($aboutBranches) > 0 ? 'd-none' : '' }}">
                                    <i class="bi bi-geo-alt fs-1 text-muted opacity-25 d-block mb-1"></i>
                                    <span class="text-muted small">{{ __('لا توجد فروع. اضغط "إضافة فرع" للبدء.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: هيرو صفحة الحجز           --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-booking-hero">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h6 class="fw-bold mb-0">{{ __('هيرو صفحة الحجز') }}</h6>
                                <p class="text-muted small mb-0">{{ __('العنوان والصورة التي تظهر في أعلى صفحة الحجز') }}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان — عربي') }}</label>
                                        <input type="text" name="store_booking_hero[title][ar]" class="form-control bg-light border-0" value="{{ $bookingHero['title']['ar'] ?? '' }}" placeholder="{{ __('احجز سيارتك الآن') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('العنوان — إنجليزي') }}</label>
                                        <input type="text" name="store_booking_hero[title][en]" class="form-control bg-light border-0" value="{{ $bookingHero['title']['en'] ?? '' }}" placeholder="Book Your Car Now">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('الوصف — عربي') }}</label>
                                        <textarea name="store_booking_hero[subtitle][ar]" rows="3" class="form-control bg-light border-0">{{ $bookingHero['subtitle']['ar'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">{{ __('الوصف — إنجليزي') }}</label>
                                        <textarea name="store_booking_hero[subtitle][en]" rows="3" class="form-control bg-light border-0">{{ $bookingHero['subtitle']['en'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('صورة الهيرو') }}</label>
                                        @if(!empty($bookingHero['image']))
                                            <div class="mb-2 rounded-3 overflow-hidden bg-light" style="max-height:120px;">
                                                <img src="{{ asset('storage/' . $bookingHero['image']) }}" class="img-fluid w-100 object-fit-cover" style="max-height:120px;">
                                            </div>
                                            <input type="hidden" name="store_booking_hero[image]" value="{{ $bookingHero['image'] }}">
                                        @endif
                                        <input type="file" name="booking_hero_image" class="form-control bg-light border-0" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: خطوات الحجز               --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-booking-steps">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('خطوات الحجز') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('الخطوات التي تظهر في صفحة الحجز') }}</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addBookingStepRow()">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة خطوة') }}
                                </button>
                            </div>
                            <div class="card-body p-4">
                                <div id="booking-steps-container" class="d-flex flex-column gap-3">
                                    @foreach($bookingSteps as $idx => $step)
                                    <div class="booking-step-row card border border-light-subtle rounded-3" id="booking-step-row-{{ $idx }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold small text-muted">{{ __('خطوة') }} {{ $idx + 1 }}</span>
                                                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeBookingStepRow({{ $idx }})"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('أيقونة (Bootstrap Icon)') }}</label>
                                                    <input type="text" name="booking_step_icon[]" class="form-control bg-light border-0 form-control-sm" value="{{ $step['icon'] ?? '' }}" placeholder="bi-calendar-check" dir="ltr">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('العنوان — عربي') }}</label>
                                                    <input type="text" name="booking_step_title_ar[]" class="form-control bg-light border-0 form-control-sm" value="{{ $step['title']['ar'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('العنوان — إنجليزي') }}</label>
                                                    <input type="text" name="booking_step_title_en[]" class="form-control bg-light border-0 form-control-sm" dir="ltr" value="{{ $step['title']['en'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('الوصف — عربي') }}</label>
                                                    <textarea name="booking_step_desc_ar[]" rows="2" class="form-control bg-light border-0 form-control-sm">{{ $step['description']['ar'] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small text-muted mb-1">{{ __('الوصف — إنجليزي') }}</label>
                                                    <textarea name="booking_step_desc_en[]" rows="2" class="form-control bg-light border-0 form-control-sm" dir="ltr">{{ $step['description']['en'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="no-booking-steps-msg" class="text-center py-5 bg-light rounded-3 {{ count($bookingSteps) > 0 ? 'd-none' : '' }}">
                                    <i class="bi bi-list-ol fs-1 text-muted opacity-25 d-block mb-1"></i>
                                    <span class="text-muted small">{{ __('لا توجد خطوات. اضغط "إضافة خطوة" للبدء.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =============================== --}}
                    {{-- TAB: أقسام صفحة الحجز          --}}
                    {{-- =============================== --}}
                    <div class="settings-pane d-none" id="tab-booking-sections">
                        @php $bsec = $bookingSections; @endphp
                        <div class="d-flex flex-column gap-3">
                            @php
                                $bookingSectionsDef = [
                                    [
                                        'id' => 'hero', 'icon' => 'bi-house', 'label' => __('قسم الهيرو'),
                                        'fields' => ['badge', 'title', 'subtitle'],
                                        'fieldLabels' => ['badge' => __('الشارة (Badge)'), 'title' => __('العنوان'), 'subtitle' => __('الوصف')],
                                        'textarea' => ['subtitle'],
                                    ],
                                    [
                                        'id' => 'form', 'icon' => 'bi-card-checklist', 'label' => __('نموذج الحجز'),
                                        'fields' => ['title', 'subtitle'],
                                        'fieldLabels' => ['title' => __('عنوان النموذج'), 'subtitle' => __('وصف النموذج')],
                                        'textarea' => ['subtitle'],
                                    ],
                                    [
                                        'id' => 'success', 'icon' => 'bi-check-circle', 'label' => __('صفحة النجاح'),
                                        'fields' => ['title', 'subtitle', 'description'],
                                        'fieldLabels' => ['title' => __('العنوان'), 'subtitle' => __('الوصف'), 'description' => __('النص التفصيلي')],
                                        'textarea' => ['subtitle', 'description'],
                                    ],
                                ];
                            @endphp

                            @foreach($bookingSectionsDef as $bIdx => $bDef)
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <button type="button" class="btn text-start p-4 d-flex align-items-center gap-3 collapsed-section-toggle border-0 bg-white rounded-4" onclick="toggleSection('b-{{ $bDef['id'] }}', this)">
                                    <i class="bi {{ $bDef['icon'] }} text-danger"></i>
                                    <span class="fw-semibold">{{ $bDef['label'] }}</span>
                                    <i class="bi bi-chevron-down ms-auto text-muted small toggle-chevron"></i>
                                </button>
                                <div class="section-body {{ $bIdx === 0 ? '' : 'd-none' }} border-top" id="b-{{ $bDef['id'] }}">
                                    <div class="p-4">
                                        <div class="row g-3">
                                            @foreach($bDef['fields'] as $bField)
                                            @php $isTA = in_array($bField, $bDef['textarea']); @endphp
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-muted">{{ $bDef['fieldLabels'][$bField] }} — {{ __('عربي') }}</label>
                                                @if($isTA)
                                                    <textarea name="store_booking_sections[{{ $bDef['id'] }}][{{ $bField }}][ar]" class="form-control bg-light border-0" rows="3">{{ $bsec[$bDef['id']][$bField]['ar'] ?? '' }}</textarea>
                                                @else
                                                    <input type="text" name="store_booking_sections[{{ $bDef['id'] }}][{{ $bField }}][ar]" class="form-control bg-light border-0" value="{{ $bsec[$bDef['id']][$bField]['ar'] ?? '' }}">
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-muted">{{ $bDef['fieldLabels'][$bField] }} — {{ __('إنجليزي') }}</label>
                                                @if($isTA)
                                                    <textarea name="store_booking_sections[{{ $bDef['id'] }}][{{ $bField }}][en]" class="form-control bg-light border-0" rows="3">{{ $bsec[$bDef['id']][$bField]['en'] ?? '' }}</textarea>
                                                @else
                                                    <input type="text" name="store_booking_sections[{{ $bDef['id'] }}][{{ $bField }}][en]" class="form-control bg-light border-0" value="{{ $bsec[$bDef['id']][$bField]['en'] ?? '' }}">
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>{{-- /settingsTabContent --}}
            </div>

            {{-- ===== SAVE SIDEBAR ===== --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top:80px; background:#0a0a0a;">
                    <div class="card-body p-4 position-relative">
                        <i class="bi bi-save position-absolute text-white opacity-10" style="font-size:80px;right:-10px;bottom:-20px;"></i>
                        <h5 class="fw-bold mb-2 text-white">{{ __('حفظ التغييرات') }}</h5>
                        <p class="small text-white opacity-50 mb-4">{{ __('تأكد من مراجعة جميع الأقسام قبل الحفظ.') }}</p>
                        @can('manage-settings')
                        <button type="submit" class="btn w-100 py-3 fw-bold rounded-3" style="background:#EDC98E1A;color:#fff;">
                            <i class="bi bi-check2-circle me-2"></i> {{ __('تحديث الإعدادات') }}
                        </button>
                        @endcan
                        <div class="mt-3 pt-3 border-top border-secondary">
                            <p class="text-white opacity-50 small mb-0 text-center" id="active-tab-label">{{ __('المعلومات الأساسية') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('css')
<style>
    .nav-group-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        padding: 12px 12px 4px;
        margin: 0;
    }
    .settings-nav-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        border: none;
        background: transparent;
        text-align: start;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13.5px;
        color: #4b5563;
        transition: background .15s, color .15s;
        cursor: pointer;
    }
    .settings-nav-btn:hover  { background: #f1f5f9; color: #111; }
    .settings-nav-btn.active { background: #fff0f0; color: rgba(235, 94, 40, 1); font-weight: 600; }
    .settings-nav-btn i { font-size: 15px; flex-shrink: 0; }

    .settings-pane { display: block; }
    .settings-pane.d-none { display: none !important; }

    .upload-preview {
        min-height: 100px;
        background: #f8fafc;
        border: 1.5px dashed #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .object-fit-cover { object-fit: cover; }
    .bg-info-subtle  { background: #e0f2fe; }

    .collapsed-section-toggle { width: 100%; cursor: pointer; }
    .collapsed-section-toggle:hover { background: #f8fafc !important; }
    .toggle-chevron { transition: transform .2s; }
    .collapsed-section-toggle.open .toggle-chevron { transform: rotate(180deg); }
</style>
@endsection

@section('scripts')
<script>
// ===== Tab Navigation =====
document.querySelectorAll('.settings-nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.settings-nav-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.settings-pane').forEach(p => p.classList.add('d-none'));
        this.classList.add('active');
        const target = document.getElementById('tab-' + this.dataset.tab);
        if (target) { target.classList.remove('d-none'); }
        const label = document.getElementById('active-tab-label');
        if (label) { label.textContent = this.textContent.trim(); }
    });
});

// ===== Collapsible Sections =====
function toggleSection(id, btn) {
    const body = document.getElementById(id);
    if (!body) return;
    const isOpen = !body.classList.contains('d-none');
    body.classList.toggle('d-none', isOpen);
    btn.classList.toggle('open', !isOpen);
}

// ===== Social Media =====
let socialCount = {{ count($socialMedia) }};
function addSocialRow() {
    const idx = socialCount++;
    document.getElementById('no-social-msg').classList.add('d-none');
    const div = document.createElement('div');
    div.className = 'social-row d-flex align-items-center gap-2 p-3 bg-light rounded-3';
    div.id = 'social-row-' + idx;
    div.innerHTML = `
        <input type="text" name="social_icon[]" class="form-control border-0 bg-white" placeholder="bi-facebook" style="max-width:150px;">
        <input type="color" name="social_color[]" class="form-control form-control-color border-0 bg-white p-1" value="#333333" style="width:40px;height:38px;">
        <input type="url" name="social_link[]" class="form-control border-0 bg-white text-start flex-grow-1" dir="ltr" placeholder="https://...">
        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeSocialRow(${idx})"><i class="bi bi-x-lg"></i></button>`;
    document.getElementById('social-container').appendChild(div);
}
function removeSocialRow(idx) {
    document.getElementById('social-row-' + idx)?.remove();
    if (!document.querySelector('.social-row')) document.getElementById('no-social-msg').classList.remove('d-none');
}

// ===== Footer Links (quick + service) =====
let footerQuickLinkCount = {{ count($footerQuickLinks) }};
let footerServiceLinkCount = {{ count($footerServiceLinks) }};
function addFooterLinkRow(group) {
    const isQuick = group === 'quick';
    const idx = isQuick ? footerQuickLinkCount++ : footerServiceLinkCount++;
    const name = isQuick ? 'footer_quick_links' : 'footer_service_links';
    const containerId = isQuick ? 'footer-quick-links-container' : 'footer-service-links-container';
    const div = document.createElement('div');
    div.className = 'footer-link-row-' + group + ' d-flex align-items-center gap-2 p-3 bg-light rounded-3';
    div.innerHTML = `
        <input type="text" name="${name}[${idx}][title][ar]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('العنوان (عربي)') }}">
        <input type="text" name="${name}[${idx}][title][en]" class="form-control border-0 bg-white shadow-none" placeholder="{{ __('العنوان (EN)') }}">
        <input type="text" name="${name}[${idx}][url]" class="form-control border-0 bg-white shadow-none text-start flex-grow-1" dir="ltr" placeholder="/about">
        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-1 lh-1" onclick="removeFooterLinkRow(this)"><i class="bi bi-x-lg"></i></button>`;
    document.getElementById(containerId).appendChild(div);
}
function removeFooterLinkRow(btn) {
    btn.closest('[class*="footer-link-row-"]')?.remove();
}

// ===== Homepage Stats =====
let statCount = {{ count($homepageStats) }};
function addStatRow() {
    const idx = statCount++;
    document.getElementById('no-stats-msg').classList.add('d-none');
    const div = document.createElement('div');
    div.className = 'stat-row d-flex align-items-center gap-2 p-3 bg-light rounded-3';
    div.id = 'stat-row-' + idx;
    div.innerHTML = `
        <input type="text" name="stat_value[]" class="form-control border-0 bg-white fw-bold text-center" placeholder="+500" style="max-width:90px;">
        <input type="text" name="stat_label[]" class="form-control border-0 bg-white flex-grow-1" placeholder="{{ __('التسمية') }}">
        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeStatRow(${idx}, 'stat-row')"><i class="bi bi-x-lg"></i></button>`;
    document.getElementById('stats-container').appendChild(div);
}
function removeStatRow(idx, prefix) {
    document.getElementById((prefix || 'stat-row') + '-' + idx)?.remove();
    if (!document.querySelector('.stat-row')) document.getElementById('no-stats-msg').classList.remove('d-none');
}

// ===== About Stats =====
let aboutStatCount = {{ count($aboutStats) }};
function addAboutStatRow() {
    const idx = aboutStatCount++;
    document.getElementById('no-about-stats-msg').classList.add('d-none');
    const div = document.createElement('div');
    div.className = 'about-stat-row d-flex align-items-center gap-2 p-3 bg-light rounded-3';
    div.id = 'about-stat-row-' + idx;
    div.innerHTML = `
        <input type="text" name="about_stat_value[]" class="form-control border-0 bg-white fw-bold text-center" placeholder="+500" style="max-width:90px;">
        <input type="text" name="about_stat_label[]" class="form-control border-0 bg-white flex-grow-1" placeholder="{{ __('التسمية') }}">
        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeAboutStatRow(${idx})"><i class="bi bi-x-lg"></i></button>`;
    document.getElementById('about-stats-container').appendChild(div);
}
function removeAboutStatRow(idx) {
    document.getElementById('about-stat-row-' + idx)?.remove();
    if (!document.querySelector('.about-stat-row')) document.getElementById('no-about-stats-msg').classList.remove('d-none');
}

// ===== Finance Stats =====
let financeStatCount = {{ count($financeStats) }};
function addFinanceStatRow() {
    const idx = financeStatCount++;
    document.getElementById('no-finance-stats-msg').classList.add('d-none');
    const div = document.createElement('div');
    div.className = 'finance-stat-row d-flex align-items-center gap-2 p-3 bg-light rounded-3';
    div.id = 'finance-stat-row-' + idx;
    div.innerHTML = `
        <input type="text" name="finance_stat_value[]" class="form-control border-0 bg-white fw-bold text-center" placeholder="500" style="max-width:90px;">
        <input type="text" name="finance_stat_label[]" class="form-control border-0 bg-white flex-grow-1" placeholder="{{ __('التسمية') }}">
        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeFinanceStatRow(${idx})"><i class="bi bi-x-lg"></i></button>`;
    document.getElementById('finance-stats-container').appendChild(div);
}
function removeFinanceStatRow(idx) {
    document.getElementById('finance-stat-row-' + idx)?.remove();
    if (!document.querySelector('.finance-stat-row')) document.getElementById('no-finance-stats-msg').classList.remove('d-none');
}

// ===== Branches =====
let branchCount = {{ count($aboutBranches) }};
function addBranchRow() {
    const idx = branchCount++;
    document.getElementById('no-branches-msg').classList.add('d-none');
    const div = document.createElement('div');
    div.className = 'branch-row card border border-light-subtle rounded-3';
    div.id = 'branch-row-' + idx;
    div.innerHTML = `
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold small text-muted">{{ __('فرع جديد') }}</span>
                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeBranchRow(${idx})"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('المدينة') }}</label><input type="text" name="branch_city[]" class="form-control bg-light border-0 form-control-sm" placeholder="{{ __('الرياض') }}"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('اسم الفرع') }}</label><input type="text" name="branch_name[]" class="form-control bg-light border-0 form-control-sm" placeholder="{{ __('فرع الرياض') }}"></div>
                <div class="col-12"><label class="form-label fw-semibold small text-muted mb-1">{{ __('العنوان') }}</label><input type="text" name="branch_address[]" class="form-control bg-light border-0 form-control-sm"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('الهاتف') }}</label><input type="text" name="branch_phone[]" class="form-control bg-light border-0 form-control-sm"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('أوقات العمل') }}</label><input type="text" name="branch_hours[]" class="form-control bg-light border-0 form-control-sm"></div>
                <div class="col-12"><label class="form-label fw-semibold small text-muted mb-1">{{ __('رابط الخريطة') }}</label><input type="url" name="branch_map_link[]" class="form-control bg-light border-0 form-control-sm text-start" dir="ltr" placeholder="https://maps.google.com/..."></div>
            </div>
        </div>`;
    document.getElementById('branches-container').appendChild(div);
}
function removeBranchRow(idx) {
    document.getElementById('branch-row-' + idx)?.remove();
    if (!document.querySelector('.branch-row')) document.getElementById('no-branches-msg').classList.remove('d-none');
}

let bookingStepCount = {{ count($bookingSteps) }};
function addBookingStepRow() {
    const idx = bookingStepCount++;
    document.getElementById('no-booking-steps-msg').classList.add('d-none');
    const div = document.createElement('div');
    div.className = 'booking-step-row card border border-light-subtle rounded-3';
    div.id = 'booking-step-row-' + idx;
    div.innerHTML = `
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold small text-muted">{{ __('خطوة جديدة') }}</span>
                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle lh-1 p-1" onclick="removeBookingStepRow(${idx})"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-12"><label class="form-label fw-semibold small text-muted mb-1">{{ __('أيقونة (Bootstrap Icon)') }}</label><input type="text" name="booking_step_icon[]" class="form-control bg-light border-0 form-control-sm" placeholder="bi-calendar-check" dir="ltr"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('العنوان — عربي') }}</label><input type="text" name="booking_step_title_ar[]" class="form-control bg-light border-0 form-control-sm"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('العنوان — إنجليزي') }}</label><input type="text" name="booking_step_title_en[]" class="form-control bg-light border-0 form-control-sm" dir="ltr"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('الوصف — عربي') }}</label><textarea name="booking_step_desc_ar[]" rows="2" class="form-control bg-light border-0 form-control-sm"></textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-muted mb-1">{{ __('الوصف — إنجليزي') }}</label><textarea name="booking_step_desc_en[]" rows="2" class="form-control bg-light border-0 form-control-sm" dir="ltr"></textarea></div>
            </div>
        </div>`;
    document.getElementById('booking-steps-container').appendChild(div);
}
function removeBookingStepRow(idx) {
    document.getElementById('booking-step-row-' + idx)?.remove();
    if (!document.querySelector('.booking-step-row')) document.getElementById('no-booking-steps-msg').classList.remove('d-none');
}

// Open first section in each accordion on load
document.querySelectorAll('.collapsed-section-toggle').forEach((btn, i) => {
    if (i === 0 || (i > 0 && btn.closest('.settings-pane') !== document.querySelectorAll('.collapsed-section-toggle')[i-1].closest('.settings-pane'))) {
        btn.classList.add('open');
    }
});
</script>
@endsection
