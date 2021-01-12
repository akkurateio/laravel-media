<akk-media-thumb :parent-props="{{ json_encode(['definition' => $entry, 'preview' => $entry->getUrl('preview'), 'model' => $entry->model, 'conversions' => $entry->getMediaConversionNames()]) }}">
    {{ $entry('preview')->attributes(['class' => 'img-fluid']) }}
</akk-media-thumb>
