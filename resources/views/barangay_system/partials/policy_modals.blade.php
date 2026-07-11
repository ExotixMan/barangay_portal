<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyPolicyModal" tabindex="-1" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyPolicyModalLabel"><i class="fas fa-user-shield me-2"></i>{{ __('messages.register_privacy_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('messages.blade_policy_modals_blade_barangay_hulong_duhat_values_your_privacy_personal_info') }}</p>
                <h6>{{ __('messages.blade_policy_modals_blade_information_we_collect') }}</h6>
                <ul>
                    <li>{{ __('messages.blade_policy_modals_blade_personal_details_provided_in_your_application_forms') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_uploaded_supporting_documents_and_contact_information') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_application_metadata_such_as_submission_date_and_refere') }}</li>
                </ul>
                <h6>{{ __('messages.blade_policy_modals_blade_how_we_use_your_information') }}</h6>
                <ul>
                    <li>{{ __('messages.blade_policy_modals_blade_to_evaluate_and_process_your_requested_barangay_service') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_to_verify_identity_and_supporting_documents') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_to_communicate_application_status_and_release_instructi') }}</li>
                </ul>
                <h6>{{ __('messages.blade_policy_modals_blade_data_protection') }}</h6>
                <ul>
                    <li>{{ __('messages.blade_policy_modals_blade_data_is_handled_by_authorized_barangay_personnel_only') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_access_is_limited_to_legitimate_government_processing_p') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_processing_follows_applicable_philippine_laws_including') }}</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.modal_close_btn') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsConditionsModal" tabindex="-1" aria-labelledby="termsConditionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsConditionsModalLabel"><i class="fas fa-file-contract me-2"></i>{{ __('messages.terms_modal_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('messages.blade_policy_modals_blade_by_using_this_portal_and_submitting_an_application_you_') }}</p>
                <ul>
                    <li>{{ __('messages.blade_policy_modals_blade_all_information_you_provide_must_be_true_complete_and_a') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_submission_of_falsified_documents_or_false_declarations') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_document_issuance_remains_subject_to_barangay_verificat') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_fees_if_applicable_are_payable_at_the_barangay_hall_dur') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_you_are_responsible_for_presenting_valid_identification') }}</li>
                    <li>{{ __('messages.blade_policy_modals_blade_barangay_hulong_duhat_may_request_additional_supporting') }}</li>
                </ul>
                <p class="mb-0">{{ __('messages.blade_policy_modals_blade_continuing_with_submission_indicates_your_acceptance_of') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.modal_close_btn') }}</button>
            </div>
        </div>
    </div>
</div>