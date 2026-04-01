<!-- Seção de Serviços -->
<section id="servicos" class="services-section">
    <div class="container">
        <h2 class="fw-bold mb-5">Categorias de Profissionais</h2>
        <div class="row g-4">
            @forelse (($homepageProfessions ?? []) as $profession)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-professional h-100">
                        <div class="card-body text-center">
                            <div class="category-icon">
                                <i class="{{ $profession->icon ?: 'fas fa-briefcase' }}"></i>
                            </div>
                            <h5 class="card-title">{{ $profession->title }}</h5>
                            <p class="card-text">{{ $profession->description }}</p>
                            @if ($profession->is_global_listing)
                                <a href="{{ url('profissionais') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Ver Todos
                                </a>
                            @else
                                <a href="{{ url('profissionais?categoria=' . $profession->slug) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Ver Profissionais
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted text-center mb-0">Nenhuma categoria disponível no momento.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
