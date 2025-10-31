<div>
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <h5 class="mb-2">
                    <i class="bi bi-person-circle me-2"></i>
                    Información del Perfil
                </h5>
                <p class="text-muted">Actualiza la información de tu cuenta y correo electrónico.</p>

                <form wire:submit.prevent="updateProfileInformation" class="mt-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>
                            Nombre
                        </label>
                        <input wire:model="name" 
                               type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>
                            Correo Electrónico
                        </label>
                        <input wire:model="email" 
                               type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                            <div class="mt-2">
                                <div class="alert alert-warning small">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Tu correo electrónico no está verificado.
                                    <button type="button" 
                                            class="btn btn-link btn-sm p-0 m-0 align-baseline"
                                            wire:click="resendVerificationNotification">
                                        Haz clic aquí para reenviar el correo de verificación.
                                    </button>
                                </div>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="alert alert-success small">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <hr class="my-4">

            <!-- Delete User Form -->
            <livewire:settings.delete-user-form />
        </div>

        <div class="col-lg-4">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Información
                    </h6>
                    <p class="small text-muted mb-0">
                        Asegúrate de usar un correo electrónico válido. Si cambias tu correo, 
                        es posible que necesites verificarlo nuevamente.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>