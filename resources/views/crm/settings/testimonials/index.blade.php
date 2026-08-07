@extends('partials.Layouts.crm-master')
@section('title', __('توصيات العملاء') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold">{{ __('توصيات العملاء (Testimonials)') }}</h4>
                <p class="text-muted mb-0 small">{{ __('إدارة آراء وتقييمات العملاء المعروضة على الموقع') }}</p>
            </div>
            @can('manage-testimonials')
            <a href="{{ route('crm.settings.testimonials.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-circle me-1"></i> {{ __('إضافة توصية') }}
            </a>
            @endcan
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-4 gap-2" id="testimonialTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 fw-bold" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-testimonials" type="button" role="tab" aria-controls="text-testimonials" aria-selected="true">
                    <i class="bi bi-chat-left-text me-1"></i> {{ __('آراء العملاء كتابة') }} ({{ $textTestimonials->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-bold" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-testimonials" type="button" role="tab" aria-controls="video-testimonials" aria-selected="false">
                    <i class="bi bi-play-btn me-1"></i> {{ __('آراء العملاء فيديوهات ريلز') }} ({{ $videoTestimonials->count() }})
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="testimonialTabsContent">
            
            <!-- 1. Text Testimonials Tab -->
            <div class="tab-pane fade show active" id="text-testimonials" role="tabpanel" aria-labelledby="text-tab">
                <div class="row g-4">
                    @forelse($textTestimonials as $testimonial)
                        <div class="col-md-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="{{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}">
                                            @if($testimonial->image)
                                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="User" class="rounded-circle object-fit-cover" width="50" height="50">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                                    <i class="bi bi-person fs-4 text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $testimonial->getTranslation('name', app()->getLocale()) }}</h6>
                                            <small class="text-muted">{{ $testimonial->getTranslation('title', app()->getLocale()) }}</small>
                                        </div>
                                        <div class="{{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }} text-warning">
                                            @for($i=0; $i<$testimonial->rating; $i++) <i class="bi bi-star-fill small"></i> @endfor
                                        </div>
                                    </div>
                                    <p class="card-text text-muted mb-0 fst-italic">
                                        "{{ mb_strimwidth($testimonial->getTranslation('content', app()->getLocale()), 0, 150, '...') }}"
                                    </p>
                                </div>
                                <div class="card-footer bg-light border-0 d-flex gap-2 p-2">
                                    @can('manage-testimonials')
                                    <a href="{{ route('crm.settings.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-white border shadow-xs rounded-2 flex-grow-1">
                                        <i class="bi bi-pencil"></i> {{ __('تعديل') }}
                                    </a>
                                    @endcan
                                    @can('manage-testimonials')
                                    <form action="{{ route('crm.settings.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه التوصية؟') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger border shadow-xs rounded-2 px-3"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="card border-0 shadow-sm rounded-4 p-5">
                                <i class="bi bi-chat-quote fs-1 d-block mb-3 opacity-25"></i>
                                <h6 class="fw-bold">{{ __('لا يوجد توصيات مكتوبة مسجلة حالياً') }}</h6>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Video Testimonials Tab -->
            <div class="tab-pane fade" id="video-testimonials" role="tabpanel" aria-labelledby="video-tab">
                <div class="row g-4">
                    @forelse($videoTestimonials as $testimonial)
                        <div class="col-md-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="{{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}">
                                            @if($testimonial->image)
                                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="User" class="rounded-circle object-fit-cover" width="50" height="50">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                                    <i class="bi bi-person fs-4 text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $testimonial->getTranslation('name', app()->getLocale()) }}</h6>
                                            <small class="text-muted">{{ $testimonial->getTranslation('title', app()->getLocale()) }}</small>
                                        </div>
                                        <div class="{{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }} text-warning">
                                            @for($i=0; $i<$testimonial->rating; $i++) <i class="bi bi-star-fill small"></i> @endfor
                                        </div>
                                    </div>
                                    <p class="card-text text-muted mb-3 fst-italic">
                                        "{{ mb_strimwidth($testimonial->getTranslation('content', app()->getLocale()), 0, 150, '...') }}"
                                    </p>
                                    
                                    @if($testimonial->review_video)
                                        <div class="position-relative rounded-3 overflow-hidden bg-dark d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <video src="{{ asset('storage/' . $testimonial->review_video) }}" class="w-100 h-100 object-fit-cover opacity-75" muted></video>
                                            <div class="position-absolute bg-white/20 backdrop-blur-md rounded-circle p-2 text-white">
                                                <i class="bi bi-play-fill fs-4"></i>
                                            </div>
                                        </div>
                                    @elseif($testimonial->review_image)
                                        <div class="rounded-3 overflow-hidden" style="height: 120px;">
                                            <img src="{{ asset('storage/' . $testimonial->review_image) }}" class="w-100 h-100 object-fit-cover" alt="Review Image">
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-light border-0 d-flex gap-2 p-2">
                                    @can('manage-testimonials')
                                    <a href="{{ route('crm.settings.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-white border shadow-xs rounded-2 flex-grow-1">
                                        <i class="bi bi-pencil"></i> {{ __('تعديل') }}
                                    </a>
                                    @endcan
                                    @can('manage-testimonials')
                                    <form action="{{ route('crm.settings.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه التوصية؟') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger border shadow-xs rounded-2 px-3"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="card border-0 shadow-sm rounded-4 p-5">
                                <i class="bi bi-play-btn fs-1 d-block mb-3 opacity-25"></i>
                                <h6 class="fw-bold">{{ __('لا يوجد آراء فيديو ريلز مسجلة حالياً') }}</h6>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

<style>
    .btn-white { background: #fff; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .object-fit-cover { object-fit: cover; }
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary) !important;
        color: #fff !important;
    }
    .nav-pills .nav-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }
</style>
