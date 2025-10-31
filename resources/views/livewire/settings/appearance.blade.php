<div>
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <h5 class="mb-2">
                    <i class="bi bi-palette me-2"></i>
                    Configuración de Apariencia
                </h5>
                <p class="text-muted">Personaliza el aspecto visual de la aplicación.</p>

                <form class="mt-4">
                    <div class="mb-3">
                        <label class="form-label d-block">
                            <i class="bi bi-brush me-1"></i>
                            Tema
                        </label>
                        <div class="btn-group" role="group" aria-label="Selección de tema">
                            <input type="radio" 
                                   class="btn-check" 
                                   name="theme" 
                                   id="light" 
                                   value="light"
                                   wire:model.live="theme">
                            <label class="btn btn-outline-primary" for="light">
                                <i class="bi bi-sun me-2"></i>
                                Claro
                            </label>

                            <input type="radio" 
                                   class="btn-check" 
                                   name="theme" 
                                   id="dark" 
                                   value="dark"
                                   wire:model.live="theme">
                            <label class="btn btn-outline-primary" for="dark">
                                <i class="bi bi-moon me-2"></i>
                                Oscuro
                            </label>

                            <input type="radio" 
                                   class="btn-check" 
                                   name="theme" 
                                   id="system" 
                                   value="system"
                                   wire:model.live="theme">
                            <label class="btn btn-outline-primary" for="system">
                                <i class="bi bi-circle-half me-2"></i>
                                Sistema
                            </label>
                        </div>
                        <small class="form-text text-muted d-block mt-2">
                            El tema se aplicará automáticamente en toda la aplicación.
                        </small>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Sobre los Temas
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li><strong>Claro:</strong> Fondo blanco con texto oscuro</li>
                        <li><strong>Oscuro:</strong> Fondo oscuro con texto claro</li>
                        <li><strong>Sistema:</strong> Usa la preferencia de tu dispositivo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>