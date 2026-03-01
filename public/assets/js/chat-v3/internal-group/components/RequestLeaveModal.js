(() => {
    const RequestLeaveModal = {
        template: `
      <div class="modal fade" id="requestLeaveModal" tabindex="-1" aria-labelledby="requestLeaveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content bg-gray-900 text-gray-200 border-0">
            <div class="modal-header border-0">
              <h5 class="modal-title" id="requestLeaveLabel">Request Leave</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <label class="form-label">Reason</label>
              <textarea v-model.trim="reason" class="form-control bg-gray-800 text-gray-200" rows="4"
                        placeholder="Tulis alasan Anda..." maxlength="500"></textarea>
              <div class="form-text text-gray-500 mt-1">Maksimal 500 karakter.</div>
              <div v-if="error" class="alert alert-danger mt-3 py-2">{{ error }}</div>
            </div>
            <div class="modal-footer border-0">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-danger" :disabled="loading || !reason" @click="submit">
                <span v-if="loading">Mengirim…</span>
                <span v-else>Submit Request</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    `,
        data() {
            return {
                reason: "",
                loading: false,
                error: ""
            };
        },
        computed: {
            gstore() {
                return useGroupStore();
            },
        },
        methods: {
            async submit() {
                this.error = "";
                if (!this.reason) {
                    this.error = "Reason wajib diisi.";
                    return;
                }
                this.loading = true;
                try {
                    await GIAPI.requestLeave(
                        this.gstore.activeGroupId,
                        this.reason
                    );
                    this.reason = "";

                    const el = document.getElementById("requestLeaveModal");
                    const modal =
                        bootstrap.Modal.getInstance(el) ||
                        new bootstrap.Modal(el);
                    modal.hide();


                    await this.gstore.refreshIfActive(
                        this.gstore.activeGroupId
                    );

                } catch (e) {
                    this.error =
                        e && e.message ? e.message : "Gagal mengirim request.";
                } finally {
                    this.loading = false;
                }
            },
        },
    };

    window.RequestLeaveModal = RequestLeaveModal;
})();