{{-- ===== Modal: تفاصيل قيد الانتظار ===== --}}
<div class="modal fade" id="globalPendingModal" tabindex="-1" aria-labelledby="globalPendingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#FFF9F0;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#FEF3C7;color:#D97706;">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="globalPendingModalLabel">{{ __('نقل الطلب إلى قيد الانتظار') }}</h6>
                        <span class="text-muted" style="font-size:12px;" id="globalPendingBookingNumber"></span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="globalPendingForm" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="pending">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">{{ __('سبب الانتظار') }} <span class="text-danger">*</span></label>
                        <input type="text" name="pending_reason" id="globalPendingReason" class="form-control bg-light border-0 shadow-none" required style="border-radius:10px; font-size:13px; padding:9px 14px;" placeholder="{{ __('مثال: العميل خارج المملكة، بانتظار الراتب...') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">{{ __('موعد وتاريخ إعادة المتابعة') }} <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="follow_up_at" id="globalPendingFollowUp" class="form-control bg-light border-0 shadow-none" required style="border-radius:10px; font-size:13px; padding:9px 14px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">{{ __('ملاحظة تفصيلية') }} <span class="text-danger">*</span></label>
                        <textarea name="note" id="globalPendingNote" class="form-control bg-light border-0 shadow-none" rows="3" required style="border-radius:10px; font-size:13px;" placeholder="{{ __('اكتب هنا تفاصيل إضافية للمتابعة...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3 flex-fill" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn py-2 px-3 fw-bold rounded-3 flex-fill text-white" style="background:#D97706;">{{ __('تأكيد ونقل لقيد الانتظار') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal: تسليم الطلب (تم التسليم) ===== --}}
<div class="modal fade" id="globalDeliveredModal" tabindex="-1" aria-labelledby="globalDeliveredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#F0FDF4;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;background:#DCFCE7;color:#16A34A;">
                        <i class="bi bi-patch-check-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-success" id="globalDeliveredModalLabel">{{ __('إتمام تسليم الطلب') }}</h6>
                        <span class="text-muted" style="font-size:12px;" id="globalDeliveredBookingNumber"></span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="globalDeliveredForm" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="received">
                <div class="modal-body px-4 py-3">
                    <div class="alert alert-success border-0 rounded-3 mb-3 p-2 d-flex align-items-center gap-2" style="font-size:12px;background:#DCFCE7;color:#166534;">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>{{ __('يرجى إدخال البيانات المالية للتسليم ليتم تحويل الطلب إلى قائمة «تم التسليم».') }}</span>
                    </div>

                    <div class="row g-3">
                        {{-- 1. سعر شراء السيارة --}}
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">{{ __('سعر شراء السيارة') }} <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" name="purchase_price" id="deliveredPurchasePrice" class="form-control bg-light border-0 shadow-none delivered-calc-input" required style="border-radius:8px 0 0 8px; font-size:13px;" placeholder="0.00">
                                <span class="input-group-text bg-light border-0 text-muted" style="font-size:11px;border-radius:0 8px 8px 0;">ر.س</span>
                            </div>
                        </div>

                        {{-- 2. سعر تعميد السيارة --}}
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">{{ __('سعر تعميد السيارة') }} <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" name="authorization_price" id="deliveredAuthPrice" class="form-control bg-light border-0 shadow-none delivered-calc-input" required style="border-radius:8px 0 0 8px; font-size:13px;" placeholder="0.00">
                                <span class="input-group-text bg-light border-0 text-muted" style="font-size:11px;border-radius:0 8px 8px 0;">ر.س</span>
                            </div>
                        </div>

                        {{-- 3. المصروفات --}}
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">{{ __('المصروفات') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" name="expenses" id="deliveredExpenses" class="form-control bg-light border-0 shadow-none delivered-calc-input" style="border-radius:8px 0 0 8px; font-size:13px;" placeholder="0.00" value="0">
                                <span class="input-group-text bg-light border-0 text-muted" style="font-size:11px;border-radius:0 8px 8px 0;">ر.س</span>
                            </div>
                        </div>

                        {{-- 4. صافي عمولة الشركة --}}
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">{{ __('صافي عمولة الشركة') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" name="net_commission" id="deliveredNetCommission" class="form-control bg-light border-0 shadow-none" style="border-radius:8px 0 0 8px; font-size:13px; font-weight:700; color:#16A34A;" placeholder="0.00">
                                <span class="input-group-text bg-light border-0 text-muted" style="font-size:11px;border-radius:0 8px 8px 0;">ر.س</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-bold small text-muted">{{ __('ملاحظات التسليم (اختياري)') }}</label>
                        <textarea name="note" class="form-control bg-light border-0 shadow-none" rows="2" style="border-radius:10px; font-size:13px;" placeholder="{{ __('ملاحظات حول استلام العميل للسيارة...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3 flex-fill" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-success py-2 px-3 fw-bold rounded-3 flex-fill text-white">{{ __('تأكيد وإتمام التسليم') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal: طلب اعتماد الإغلاق للمشرف ===== --}}
<div class="modal fade" id="globalRequestCloseModal" tabindex="-1" aria-labelledby="globalRequestCloseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#FEF2F2;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#FEE2E2;color:#DC2626;">
                        <i class="bi bi-x-circle fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-danger" id="globalRequestCloseModalLabel">{{ __('طلب إغلاق الحجز') }}</h6>
                        <span class="text-muted" style="font-size:12px;" id="globalRequestCloseBookingNumber"></span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="globalRequestCloseForm" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" id="globalRequestCloseTargetStatus">
                <div class="modal-body px-4 py-3">
                    <div class="alert alert-warning border-0 rounded-3 text-warning-dark p-2" style="font-size: 12px;background:#FEF3C7;color:#92400E;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        {{ __('سيتم إرسال هذا الطلب إلى المشرف للاعتماد والموافقة على الإغلاق.') }}
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">{{ __('المرحلة المطلوبة') }}</label>
                        <input type="text" id="globalRequestCloseTargetLabel" class="form-control bg-light border-0 shadow-none fw-bold" readonly style="border-radius:10px; font-size:13px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">{{ __('ملاحظة أو تبرير الإغلاق للمشرف') }} <span class="text-danger">*</span></label>
                        <textarea name="note" class="form-control bg-light border-0 shadow-none" rows="3" required style="border-radius:10px; font-size:13px;" placeholder="{{ __('اكتب هنا تبرير الإغلاق أو الملاحظات...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3 flex-fill" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-danger py-2 px-3 fw-bold rounded-3 flex-fill">{{ __('إرسال الطلب للمشرف') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let activeStatusSelect = null;
    let originalStatusValue = null;

    function handleBookingStatusSelectChange(selectEl, bookingId, updateUrl, isAdmin) {
        activeStatusSelect = selectEl;
        originalStatusValue = selectEl.getAttribute('data-current-status') || selectEl.value;
        const targetStatus = selectEl.value;
        const targetLabel = selectEl.options[selectEl.selectedIndex].text;
        const isClose = selectEl.options[selectEl.selectedIndex].getAttribute('data-close') === '1' || targetStatus.startsWith('lost_');

        if (targetStatus === 'pending') {
            const modalEl = document.getElementById('globalPendingModal');
            document.getElementById('globalPendingForm').action = updateUrl;
            document.getElementById('globalPendingBookingNumber').innerText = 'طلب #' + bookingId;
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            return;
        }

        if (targetStatus === 'received') {
            const modalEl = document.getElementById('globalDeliveredModal');
            document.getElementById('globalDeliveredForm').action = updateUrl;
            document.getElementById('globalDeliveredBookingNumber').innerText = 'طلب #' + bookingId;
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            return;
        }

        if (isClose && !isAdmin) {
            const modalEl = document.getElementById('globalRequestCloseModal');
            document.getElementById('globalRequestCloseForm').action = updateUrl;
            document.getElementById('globalRequestCloseTargetStatus').value = targetStatus;
            document.getElementById('globalRequestCloseTargetLabel').value = targetLabel;
            document.getElementById('globalRequestCloseBookingNumber').innerText = 'طلب #' + bookingId;
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            return;
        }

        // Direct submission for normal active statuses or admin close
        selectEl.form.submit();
    }

    // Reset select on modal dismiss
    document.addEventListener('DOMContentLoaded', function() {
        ['globalPendingModal', 'globalDeliveredModal', 'globalRequestCloseModal'].forEach(id => {
            const modalEl = document.getElementById(id);
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function() {
                    if (activeStatusSelect && originalStatusValue) {
                        activeStatusSelect.value = originalStatusValue;
                    }
                });
            }
        });

        // Live calculation for delivered modal: net_commission = auth_price - purchase_price - expenses
        const purchaseInput = document.getElementById('deliveredPurchasePrice');
        const authInput = document.getElementById('deliveredAuthPrice');
        const expensesInput = document.getElementById('deliveredExpenses');
        const netCommissionInput = document.getElementById('deliveredNetCommission');

        function calculateDeliveredCommission() {
            if (!purchaseInput || !authInput || !expensesInput || !netCommissionInput) return;
            const authVal = parseFloat(authInput.value) || 0;
            const purchaseVal = parseFloat(purchaseInput.value) || 0;
            const expensesVal = parseFloat(expensesInput.value) || 0;
            
            if (authVal > 0 || purchaseVal > 0) {
                const commission = authVal - purchaseVal - expensesVal;
                netCommissionInput.value = commission.toFixed(2);
            }
        }

        if (purchaseInput && authInput && expensesInput) {
            [purchaseInput, authInput, expensesInput].forEach(inp => {
                inp.addEventListener('input', calculateDeliveredCommission);
            });
        }
    });
</script>
