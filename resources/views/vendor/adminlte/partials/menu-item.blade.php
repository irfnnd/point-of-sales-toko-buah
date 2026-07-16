@php
    use ColorlibHQ\AdminLte\Menu\MenuItemHelper;
    $isHeader = MenuItemHelper::isHeader($item);
    $isSubmenu = MenuItemHelper::isSubmenu($item);
    $isActive = ! empty($item['active']);
    $icon = $isActive && isset($item['icon_active']) ? $item['icon_active'] : ($item['icon'] ?? 'bi bi-circle');
@endphp

@if ($isHeader)
    <li class="nav-header">{{ $item['header'] }}</li>
@elseif ($isSubmenu)
    <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ $isActive ? 'active' : '' }}" role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
            <i class="nav-icon {{ $icon }} {{ isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '' }}" aria-hidden="true"></i>
            <p>
                {{ $item['text'] }}
                <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                @isset($item['label'])
                    <span class="nav-badge badge text-bg-{{ $item['label_color'] ?? 'primary' }} me-3">{{ $item['label'] }}</span>
                @endisset
            </p>
        </a>
        <ul class="nav nav-treeview">
            @foreach ($item['submenu'] as $child)
                @include('adminlte::partials.menu-item', ['item' => $child])
            @endforeach
        </ul>
    </li>
@else
    <li class="nav-item">
        <a href="{{ $item['href'] ?? '#' }}" class="nav-link {{ $isActive ? 'active' : '' }}"
           @isset($item['target']) target="{{ $item['target'] }}" rel="noopener" @endisset>
            <i class="nav-icon {{ $icon }} {{ isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '' }}" aria-hidden="true"></i>
            <p>
                {{ $item['text'] }}
                @isset($item['label'])
                    <span class="nav-badge badge text-bg-{{ $item['label_color'] ?? 'primary' }} me-3">{{ $item['label'] }}</span>
                @endisset
            </p>
        </a>
    </li>
@endif
