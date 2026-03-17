<!-- Seção de Serviços -->
<section id="servicos" class="services-section">
    <div class="container">
        <h2 class="fw-bold mb-5">Categorias de Profissionais</h2>
        <div class="row g-4">
            <!-- Vigias -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title">Vigias</h5>
                        <p class="card-text">Profissionais experientes para segurança residencial e comercial com
                            responsabilidade.</p>
                        <a href="{{ url('profissionais?categoria=vigia') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Seguranças -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-user-secret"></i>
                        </div>
                        <h5 class="card-title">Seguranças</h5>
                        <p class="card-text">Segurança profissional para eventos, empresas e residências com
                            certificação.</p>
                        <a href="{{ url('profissionais?categoria=seguranca') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Costureiras -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-scissors"></i>
                        </div>
                        <h5 class="card-title">Costureiras</h5>
                        <p class="card-text">Serviços de costura, consertos e confecção de roupas com qualidade
                            garantida.</p>
                        <a href="{{ url('profissionais?categoria=costureira') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profissionais de TI -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h5 class="card-title">Profissionais de TI</h5>
                        <p class="card-text">Suporte técnico, manutenção e consultoria em informática com expertise.</p>
                        <a href="{{ url('profissionais?categoria=ti') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Babás -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h5 class="card-title">Babás</h5>
                        <p class="card-text">Cuidado profissional e responsável com crianças com experiência comprovada.
                        </p>
                        <a href="{{ url('profissionais?categoria=baba') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Faxineiras -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-broom"></i>
                        </div>
                        <h5 class="card-title">Faxineiras</h5>
                        <p class="card-text">Limpeza profissional para residências e comercial com produtos de
                            qualidade.</p>
                        <a href="{{ url('profissionais?categoria=faxineira') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Domésticas -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5 class="card-title">Domésticas</h5>
                        <p class="card-text">Serviços gerais de limpeza e organização do lar com dedicação.</p>
                        <a href="{{ url('profissionais?categoria=domestica') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cozinheiras -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h5 class="card-title">Cozinheiras</h5>
                        <p class="card-text">Preparo de refeições e serviços de catering com receitas especiais.</p>
                        <a href="{{ url('profissionais?categoria=cozinheira') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Outros Serviços -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-professional h-100">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="card-title">Outros Serviços</h5>
                        <p class="card-text">Explore outras categorias de profissionais disponíveis em Batatais.</p>
                        <a href="{{ url('profissionais') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Todos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
