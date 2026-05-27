@extends('layouts.app')
@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner(s) de Campanhas em Destaque -->
    @php
        $user = auth()->user();
        $showMarketingForUser = $user && in_array($user->user_type_id, [1, 2]); // Admin e Franqueado
    @endphp
    @if($showMarketingForUser && isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
        @php $camp = $featuredCampaigns->first(); @endphp
        @php
            $desk = $camp->banner_desktop_url;
            $mob = $camp->banner_mobile_url ?: $desk;
            $desk = $desk ?: $mob;
        @endphp
        <div class="w-full flex items-center justify-center mb-8 min-h-[280px] md:min-h-[400px] lg:h-[600px]">
            <a href="{{ route('campanhas.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $camp->id]) }}" class="block w-full h-full">
                @if($desk || $mob)
                    <img src="{{ $desk }}" alt="{{ $camp->name }}" class="hidden md:block w-full h-full max-h-[600px] object-cover" />
                    <img src="{{ $mob }}" alt="{{ $camp->name }}" class="md:hidden w-full aspect-square max-h-[100vw] object-cover" />
                @else
                    <div class="w-full flex items-center justify-center bg-[#910039] py-16 md:py-24 text-white">
                        <div class="text-center px-4">
                            <i class="fas fa-image text-5xl mb-3 opacity-60"></i>
                            <p class="text-sm opacity-90">Campanha em destaque — banners em configuração</p>
                        </div>
                    </div>
                @endif
            </a>
        </div>
    @elseif($showMarketingForUser)
        <!-- Placeholder quando não há campanhas featured -->
        <div class="w-full flex items-center justify-center mb-8 bg-[#910039] py-12 md:py-16">
            <div class="text-center text-white px-4">
                <i class="fas fa-bullhorn text-6xl mb-4 opacity-50"></i>
                <h3 class="text-xl font-bold mb-2">Campanhas de Marketing</h3>
                <p class="text-sm opacity-75">Em breve, novas campanhas exclusivas para franqueados</p>
            </div>
        </div>
    @else
        <!-- Mensagem de boas-vindas para Lojista e Representante -->
        <div class="w-full flex items-center justify-center mb-8 bg-[#910039] py-12 md:py-16">
            <div class="text-center text-white max-w-4xl px-4">
                <i class="fas fa-heart text-6xl mb-6 opacity-75"></i>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Bem-vindos à Plataforma Orthocrin</h2>
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
                        <a href="{{ route('produtos.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $produto->id]) }}" class="flex items-center gap-1 text-[#910039] text-xs">
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
            <a href="{{ route('produtos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="bg-[#910039] text-white px-8 py-2 rounded-full font-semibold">Ver todos</a>
        </div>
    </div>
    <!-- Acervo Digital -->
    <div class="bg-[#FAFAFA]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-end gap-8">
            <div class="flex-1 min-w-0 py-12">
                <div class="text-[#747474] font-bold text-sm mb-1">Plataforma UniOrthocrin</div>
                <div class="text-[#910039] font-bold text-3xl mb-4">Acervo Digital</div>
                <p class="text-gray-700 mb-8 text-base">Bem-vindos à nossa plataforma de acervo digital! Aqui, franquias, representantes e lojistas Orthocrin encontram uma seleção completa de materiais de marketing e produtos, incluindo campanhas prontas para suas mídias digitais e e-commerce.</p>
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
            <div class="flex-1 flex justify-center items-end">
                <img src="{{ asset('images/unniorthocrin-home-middle.png') }}" alt="Acervo Digital" class="">
            </div>
        </div>
    </div>
    <!-- Novos Treinamentos e Radar -->
    <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <div class="md:col-span-2">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-[#910039] text-2xl font-bold uppercase">NOVOS TREINAMENTOS</h2>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                @if(isset($treinamentos))
                @forelse($treinamentos as $treinamento)
            <div class="dashboard-card training-card bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                @php
                    $imageFile = $treinamento->files->firstWhere('type', 'image');
                    $thumbSrc = $treinamento->thumbnail_path
                        ? url('/' . ltrim($treinamento->thumbnail_path, '/'))
                        : ($imageFile ? $imageFile->url : 'https://placehold.co/600x600?text=Treinamento');
                @endphp
                <img src="{{ $thumbSrc }}" alt="{{ $treinamento->name }}" class="w-full h-32 object-cover rounded-t-lg">
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[#910039] font-bold text-base mb-1">{{ $treinamento->name }}</div>
                        <div class="text-gray-500 text-sm mb-2">{{ $treinamento->category->name ?? 'Sem categoria' }}</div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <a href="{{ route('treinamentos.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $treinamento->id]) }}" class="flex items-center gap-1 text-[#910039] text-xs hover:underline">
                            <i class="fa-regular fa-eye"></i>
                            Ver treinamento
                        </a>
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
            <div class="flex"><a href="{{ route('treinamentos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="bg-[#910039] text-white px-8 py-2 rounded-full font-semibold">Ver todos</a></div>
        </div>
        <div class="bg-[#FAFAFA] border border-gray-200 rounded-lg p-6 w-full md:w-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[#910039] text-xl font-bold">Radar</h2>
                @if(isset($news) && $news->count() > 0)
                <div class="text-sm text-gray-500 text-right">
                    <span id="total-news" class="font-semibold">{{ $news->count() }}</span> notícias
                </div>
                @endif
            </div>
            <div class="space-y-6 mb-4">
                @if(isset($news))
                @forelse($news as $noticia)
                <div class="pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                    <div class="text-[#910039] font-semibold text-sm mb-1">{{ $noticia->title }}</div>
                    <div class="text-gray-500 text-xs mb-1">{{ $noticia->created_at->format('d/m/Y') }}</div>
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
            <div class="flex justify-end">
                <a href="{{ route('radar.list', ['profile_slug' => session('active_profile_slug')]) }}" class="inline-flex items-center text-xs text-[#910039] hover:underline mt-2">
                    Ir para o Radar
                    <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- Blocos finais -->
    <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 {{ $showMarketingForUser ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-6">
        @canSee('bloco_marketing')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Marketing</div>
            <div class="text-gray-700 text-sm mb-6">Materiais exclusivos das campanhas Orthocrin para impulsionar sua marca. Acesse vídeos de alta qualidade, imagens otimizadas para redes sociais, e peças gráficas profissionais para todos os canais de comunicação.</div>
            <a href="{{ route('campanhas.list', ['profile_slug' => session('active_profile_slug')]) }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
        @canSee('bloco_produtos')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Produtos</div>
            <div class="text-gray-700 text-sm mb-6">Galeria completa de produtos Orthocrin em imagens e vídeos de alta definição. Recursos visuais premium para potencializar suas vendas e destacar a qualidade superior dos nossos produtos.</div>
            <a href="{{ route('produtos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
        @canSee('bloco_treinamentos')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Treinamentos</div>
            <div class="text-gray-700 text-sm mb-6">Programa completo de capacitação profissional Orthocrin. Acesse conteúdos especializados em vendas e marketing, com vídeos interativos e manuais detalhados em PDF para maximizar seus resultados.</div>
            <a href="{{ route('treinamentos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
        </div>
        @endcanSee
        @canSee('bloco_biblioteca')
        <div class="dashboard-card bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col items-start">
            <div class="text-[#910039] font-bold text-lg mb-2">Biblioteca</div>
            <div class="text-gray-700 text-sm mb-6">Acervo digital completo da marca Orthocrin. Encontre todos os materiais institucionais, desde documentos impressos até recursos digitais, organizados para facilitar sua consulta e utilização.</div>
            <a href="{{ route('biblioteca.list', ['profile_slug' => session('active_profile_slug')]) }}" class="bg-[#910039] text-white px-6 py-2 rounded font-semibold text-sm">Ver todas</a>
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
    const csrf = form.querySelector('input[name=_token]')?.value || '';
    try {
        const resp = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf }, body: formData });
        const ct = (resp.headers.get('Content-Type') || '').toLowerCase();
        if (ct.includes('application/json')) {
            const data = await resp.json();
            if (data && data.success && data.downloadUrl) {
                window.location.href = data.downloadUrl;
            } else {
                alert(data?.message || 'Falha ao preparar download.');
            }
        } else {
            const blob = await resp.blob();
            if (!resp.ok) {
                alert('Não foi possível baixar o arquivo.');
                return false;
            }
            let filename = 'download';
            const cd = resp.headers.get('Content-Disposition');
            if (cd) {
                const utf8 = /filename\*=(?:UTF-8'')?([^;]+)/i.exec(cd);
                const plain = /filename="([^"]+)"/i.exec(cd) || /filename=([^;\s]+)/i.exec(cd);
                if (utf8) {
                    filename = decodeURIComponent(utf8[1].trim().replace(/^["']|["']$/g, ''));
                } else if (plain) {
                    filename = plain[1].trim().replace(/^["']|["']$/g, '');
                }
            }
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
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
