@extends('layouts.app')
@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner(s) de Campanhas em Destaque -->
    @php
        $user = auth()->user();
        $showMarketingForUser = $user && in_array($user->user_type_id, [1, 2]); // Admin e Franqueado
    @endphp
    @if($showMarketingForUser && isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
        @if($featuredCampaigns->count() === 1)
            <div class="w-full h-56 md:h-80 flex items-center justify-center mb-8">
                <img src="/{{ $featuredCampaigns->first()->banner_path }}" alt="Banner Campanha" class="w-full h-full object-cover" />
            </div>
        @else
            <div class="w-full mb-8">
                <div class="relative overflow-hidden">
                    <button type="button" id="carousel-prev" class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/70 hover:bg-white text-gray-800 rounded-full h-10 w-10 items-center justify-center shadow">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" id="carousel-next" class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/70 hover:bg-white text-gray-800 rounded-full h-10 w-10 items-center justify-center shadow">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                    <div id="banner-track" class="flex gap-4 overflow-x-auto snap-x scroll-smooth">
                        @foreach($featuredCampaigns as $camp)
                            <a href="{{ route('marketing.detail', $camp->id) }}" class="block flex-shrink-0 w-full snap-start">
                                <div class="w-full h-56 md:h-80 bg-gray-100">
                                    <img src="/{{ $camp->banner_path }}" alt="{{ $camp->name }}" class="w-full h-full object-cover" />
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @elseif($showMarketingForUser)
        <!-- Placeholder quando não há campanhas featured -->
        <div class="w-full h-56 md:h-80 flex items-center justify-center mb-8 bg-[#910039]">
            <div class="text-center text-white">
                <i class="fas fa-bullhorn text-6xl mb-4 opacity-50"></i>
                <h3 class="text-xl font-bold mb-2">Campanhas de Marketing</h3>
                <p class="text-sm opacity-75">Em breve, novas campanhas exclusivas para franqueados</p>
            </div>
        </div>
    @else
        <!-- Mensagem de boas-vindas para Lojista e Representante -->
        <div class="w-full h-56 md:h-80 flex items-center justify-center mb-8 bg-[#910039]">
            <div class="text-center text-white max-w-4xl px-4">
                <i class="fas fa-heart text-6xl mb-6 opacity-75"></i>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Bem-vindo à Plataforma Orthocrin</h2>
                <p class="text-lg md:text-xl opacity-90 mb-4">Sua jornada de sucesso começa aqui</p>
                <p class="text-sm md:text-base opacity-75 leading-relaxed">
                    Acesse produtos exclusivos, treinamentos especializados e materiais de alta qualidade 
                    para impulsionar seus resultados e fortalecer sua parceria com a Orthocrin.
                </p>
            </div>
        </div>
    @endif
    <!-- Últimos Produtos -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-[#910039] text-2xl font-bold uppercase">ÚLTIMOS PRODUTOS</h2>
            <div class="text-sm text-gray-500">
                Total: <span id="total-products" class="font-semibold">{{ $produtos->count() }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-8">
            @forelse($produtos as $produto)
            <div class="dashboard-card product-card bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                <img src="{{ $produto->thumbnail_path ? url('/' . $produto->thumbnail_path) : ($produto->images->first() ? $produto->images->first()->url : 'https://placehold.co/600x600?text=Produto') }}" alt="{{ $produto->name }}" class="w-full h-32 object-cover rounded-t-lg">
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[#910039] font-bold text-base mb-1">{{ $produto->name }}</div>
                        <div class="text-gray-500 text-sm mb-2">
                            {{ $produto->category->name ?? 'Sem categoria' }}
                            @if($produto->series)
                                ・ {{ $produto->series->name }}
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <a href="{{ route('produtos.detail', $produto->id) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                            <i class="fa-regular fa-eye"></i>
                            Detalhes
                        </a>
                        @if($produto->canBeDownloadedBy(auth()->user()))
                        <form method="POST" action="{{ route('download.files') }}" class="flex items-center gap-1" onsubmit="return handleDownloadSubmit(event, this);">
                            @csrf
                            <input type="hidden" name="content_type" value="product">
                            <input type="hidden" name="content_id" value="{{ $produto->id }}">
                            <input type="hidden" name="type" value="all">
                            <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-solid fa-download"></i>
                                Download .zip
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8">
                Nenhum produto encontrado.
            </div>
            @endforelse
        </div>
        <div class="flex justify-center">
            <a href="{{ route('produtos.list') }}" class="bg-[#910039] text-white px-8 py-2 rounded-full font-semibold">Ver todos</a>
        </div>
    </div>
    <!-- Acervo Digital -->
    <div class="bg-[#FAFAFA] py-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1 min-w-0">
                <div class="text-[#747474] font-bold text-sm mb-1">Plataforma UniOrthocrin</div>
                <div class="text-[#910039] font-bold text-3xl mb-4">Acervo Digital</div>
                <p class="text-gray-700 mb-8 text-base">Bem-vindo à nossa plataforma de acervo digital! Aqui, franquias, representantes e lojistas Orthocrin encontram uma seleção completa de materiais de marketing e produtos, incluindo campanhas prontas para suas mídias digitais e e-commerce.</p>
                <ul class="space-y-6">
                    <li class="flex items-start gap-4">
                        <span class="flex-shrink-0 flex flex-col items-center justify-center w-8 h-8 rounded-md bg-[#910039]">
                            <i class="fa-solid fa-magnifying-glass text-[#FFD600] text-md"></i>
                        </span>
                        <span class="text-gray-700 text-base leading-relaxed">Encontre tudo o que você precisa em um só lugar! Digite o que procura e acesse materiais de alta qualidade para suas campanhas e divulgação de produtos Orthocrin.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="flex-shrink-0 flex flex-col items-center justify-center w-8 h-8 rounded-md bg-[#910039]">
                            <i class="fa-solid fa-download text-[#FFD600] text-md"></i>
                        </span>
                        <span class="text-gray-700 text-base leading-relaxed">Escolha como impulsionar suas vendas: baixe arquivos individualmente para necessidades específicas ou pacotes completos para campanhas abrangentes.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="flex-shrink-0 flex flex-col items-center justify-center w-8 h-8 rounded-md bg-[#910039]">
                            <i class="fa-solid fa-star text-[#FFD600] text-md"></i>
                        </span>
                        <span class="text-gray-700 text-base leading-relaxed">Conteúdo pronto para brilhar! Utilize os materiais Orthocrin em suas redes sociais, e-commerce e outros canais digitais. Tudo otimizado para gerar resultados.</span>
                    </li>
                </ul>
            </div>
            <div class="flex-1 flex justify-center items-center">
                <img src="https://placehold.co/420x320" alt="Acervo Digital" class="w-96 max-w-full rounded-lg shadow">
            </div>
        </div>
    </div>
    <!-- Novos Treinamentos e Radar -->
    <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <div class="md:col-span-2">
,            <div class="flex justify-between items-center mb-8">
                <h2 class="text-[#910039] text-2xl font-bold uppercase">NOVOS TREINAMENTOS</h2>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                @if(isset($treinamentos))
                @forelse($treinamentos as $treinamento)
                <div class="dashboard-card training-card bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                    <img src="{{ $treinamento->thumbnail_path ? url('/private/' . $treinamento->thumbnail_path) : ($treinamento->files->first() ? $treinamento->files->first()->url : 'https://placehold.co/600x600?text=Treinamento') }}" alt="{{ $treinamento->name }}" class="w-full h-32 object-cover rounded-t-lg">
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1">{{ $treinamento->name }}</div>
                            <div class="text-gray-500 text-sm mb-2">{{ $treinamento->category->name ?? 'Sem categoria' }}</div>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <i class="fa-regular fa-eye text-[#910039]"></i>
                            <span class="text-xs text-gray-700">Ver treinamento</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-8">
                    Nenhum treinamento encontrado.
                </div>
                @endforelse
                @else
                <div class="col-span-full text-center text-gray-500 py-8">
                    Variável treinamentos não definida.
                </div>
                @endif
            </div>
            <div class="flex"><a href="{{ route('treinamentos.list') }}" class="bg-[#910039] text-white px-8 py-2 rounded-full font-semibold">Ver todos</a></div>
        </div>
        <div class="bg-[#FAFAFA] border border-gray-200 rounded-lg p-6 w-full md:w-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[#910039] text-xl font-bold">Radar</h2>
                <div class="text-sm text-gray-500">
                    <span id="total-news" class="font-semibold">{{ isset($news) ? $news->count() : 0 }}</span> notícias
                </div>
            </div>
            <div class="space-y-6 mb-6">
                @if(isset($news))
                @forelse($news as $noticia)
                <div class="pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                    <div class="text-[#910039] font-semibold text-sm mb-1">{{ $noticia->title }}</div>
                    <div class="text-gray-500 text-xs mb-1">{{ $noticia->published_at ? $noticia->published_at->format('d/m/Y') : 'Data não informada' }}</div>
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-eye text-[#910039]"></i>
                        <span class="text-xs text-gray-700">Ver mais</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">
                    Nenhuma notícia encontrada.
                </div>
                @endforelse
                @else
                <div class="text-center text-gray-500 py-4">
                    Nenhuma notícia disponível.
                </div>
                @endif
            </div>
            <a href="{{ route('news.list') }}" class="bg-[#910039] text-white px-6 py-1 rounded-full font-semibold text-sm">Ver todas</a>
        </div>
    </div>
    <!-- Blocos finais -->
    <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 {{ $showMarketingForUser ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-6">
        @canSee('bloco_marketing')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Marketing</div>
            <div class="text-gray-700 text-sm mb-6">Materiais exclusivos das campanhas Orthocrin para impulsionar sua marca. Acesse vídeos de alta qualidade, imagens otimizadas para redes sociais, e peças gráficas profissionais para todos os canais de comunicação.</div>
            <a href="{{ route('marketing.list') }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
        @canSee('bloco_produtos')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Produtos</div>
            <div class="text-gray-700 text-sm mb-6">Galeria completa de produtos Orthocrin em imagens e vídeos de alta definição. Recursos visuais premium para potencializar suas vendas e destacar a qualidade superior dos nossos produtos.</div>
            <a href="{{ route('produtos.list') }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
        @canSee('bloco_treinamentos')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Treinamentos</div>
            <div class="text-gray-700 text-sm mb-6">Programa completo de capacitação profissional Orthocrin. Acesse conteúdos especializados em vendas e marketing, com vídeos interativos e manuais detalhados em PDF para maximizar seus resultados.</div>
            <a href="{{ route('treinamentos.list') }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
        @canSee('bloco_biblioteca')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Biblioteca</div>
            <div class="text-gray-700 text-sm mb-6">Acervo digital completo da marca Orthocrin. Encontre todos os materiais institucionais, desde documentos impressos até recursos digitais, organizados para facilitar sua consulta e utilização.</div>
            <a href="{{ route('biblioteca.list') }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
    </div>
</div>
@endsection 
@push('scripts')
<script>
async function handleDownloadSubmit(e, form) {
    e.preventDefault();
    const formData = new FormData(form);
    try {
        const resp = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value }, body: formData });
        const data = await resp.json();
        if (data && data.success && data.downloadUrl) {
            window.location.href = data.downloadUrl;
        } else {
            alert(data.message || 'Falha ao preparar download.');
        }
    } catch (err) {
        alert('Erro de rede ao iniciar download');
    }
    return false;
}
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('banner-track');
    if (!track) return;
    const prev = document.getElementById('carousel-prev');
    const next = document.getElementById('carousel-next');

    const scrollOne = (dir = 1) => {
        const width = track.clientWidth;
        track.scrollBy({ left: dir * width, behavior: 'smooth' });
    };

    if (prev && next) {
        prev.addEventListener('click', () => scrollOne(-1));
        next.addEventListener('click', () => scrollOne(1));
    }

    // Auto-play: avança a cada 6s, pausa ao passar mouse
    let timer = setInterval(() => scrollOne(1), 6000);
    const stop = () => timer && clearInterval(timer);
    const start = () => { stop(); timer = setInterval(() => scrollOne(1), 6000); };
    track.addEventListener('mouseenter', stop);
    track.addEventListener('mouseleave', start);
});
</script>
@endpush
