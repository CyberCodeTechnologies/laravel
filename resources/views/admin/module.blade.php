@extends('admin.layouts.app')

@section('title', $pageTitle ?? $moduleTitle)

@section('header', $pageTitle ?? $moduleTitle)

@section('admin_content')
    <div class="zoho-page-header">
        @if (!empty($breadcrumb))
            <div class="zoho-breadcrumb">
                @foreach ($breadcrumb as $i => $crumb)
                    @if ($i > 0)<span class="sep">/</span>@endif
                    @if (!empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <span>{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </div>
        @endif
        @if (!empty($subtitle))
            <p class="subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="zoho-toolbar">
        <div class="zoho-tabs" style="margin:0;border:none;">
            @foreach ($tabs ?? ['All'] as $tab)
                <button type="button" class="zoho-tab {{ $loop->first ? 'active' : '' }}">{{ $tab }}</button>
            @endforeach
        </div>
        <div class="zoho-toolbar-actions">
            @if (!empty($createLabel))
                <a href="#" class="zoho-btn zoho-btn-primary">+ {{ $createLabel }}</a>
            @endif
            <button type="button" class="zoho-btn zoho-btn-secondary">⋮ More</button>
        </div>
    </div>

    <div class="zoho-card">
        @if (!empty($filterChips))
            <div class="zoho-filter-bar">
                @foreach ($filterChips as $i => $chip)
                    <button type="button" class="zoho-filter-chip {{ $i === 0 ? 'active' : '' }}">{{ $chip }}</button>
                @endforeach
            </div>
        @endif
        <div class="zoho-card-body" style="{{ !empty($filterChips) ? 'padding-top:0;' : '' }}">
            @if (!empty($tableHeaders) && !empty($tableRows))
                <div class="zoho-table-wrap">
                    <table class="zoho-table">
                        <thead>
                            <tr>
                                @foreach ($tableHeaders as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableRows as $row)
                                <tr>
                                    @foreach ($row as $cell)
                                        <td>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="zoho-empty">
                    <p>No records yet. Click <strong>+ {{ $createLabel ?? 'New' }}</strong> to get started.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
