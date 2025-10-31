<div>
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <h5 class="mb-2">
                    <i class="bi bi-shield-lock me-2"></i>
                    Actualizar Contraseña
                </h5>
                <p class="text-muted">Asegúrate de usar una contraseña larga y segura para proteger tu cuenta.</p>

                <form wire:submit.prevent="updatePassword" class="mt-4">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="bi bi-key me-1"></i>
                            Contraseña Actual
                        </label>
                        <input wire:model="current_password" 
                               type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            Nueva Contraseña
                        </label>
                        <input wire:model="password" 
                               type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">Mínimo 8 caracteres.</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>
                            Confirmar Contraseña
                        </label>
                        <input wire:model="password_confirmation" 
                               type="password" 
                               class="form-control"
                               id="password_confirmation" 
                               required>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Recomendaciones de Seguridad
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Usa al menos 8 caracteres</li>
                        <li>Combina letras, números y símbolos</li>
                        <li>No uses información personal</li>
                        <li>No reutilices contraseñas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>