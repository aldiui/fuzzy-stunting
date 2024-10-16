<div class="overflow-x-auto">
    <table class="min-w-full divide-y w-full bg-white border border-gray-200 mb-10">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai
                    Linguistik</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fuzzy</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interval</th>
            </tr>
        </thead>
        <tbody>
            @if ($fuzzy && is_array($fuzzy))
                @foreach ($fuzzy as $item)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-4 px-4 text-sm text-gray-900">{{ htmlspecialchars($item['status']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">{{ htmlspecialchars($item['fuzzy']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ implode(', ', array_map('strval', $item['interval'])) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="py-4 px-4 text-sm text-red-500 text-center">No data available</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
