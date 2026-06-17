@props([
    'title',
    'subtitle',
    'value',
    'columnClass' => 'col-md-6 col-xl-3',
    'cardClass' => '',
    'textClass' => '',
    'numberClass' => '',
])

<div class="{{ $columnClass }}">
    <div class="card mb-3 widget-content {{ $cardClass }}">
        <div class="widget-content-wrapper {{ $textClass }}">
            <div class="widget-content-left">
                <div class="widget-heading">{{ $title }}</div>
                <div class="widget-subheading">{{ $subtitle }}</div>
            </div>
            <div class="widget-content-right">
                <div class="widget-numbers {{ $numberClass }}">{{ $value }}</div>
            </div>
        </div>
    </div>
</div>
