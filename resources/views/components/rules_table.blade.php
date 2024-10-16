<div class="overflow-x-auto">
    <table class="min-w-full divide-y w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rule</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BB/U</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TB/U</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BB/TB</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Anak
                </th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">A -Predikat</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Defuzifikasi Kondisi Anak</th>
            </tr>
        </thead>
        <tbody>
            @if ($rules && is_array($rules))
                @foreach ($rules as $item)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-4 px-4 text-sm text-gray-900">{{ htmlspecialchars($item['id']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ htmlspecialchars($item['variabel_fuzzy_bbu']['nama']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ htmlspecialchars($item['variabel_fuzzy_tbu']['nama']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ htmlspecialchars($item['variabel_fuzzy_bbtb']['nama']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ htmlspecialchars($item['index_fuzzy']['nama']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ htmlspecialchars($item['min']) }}</td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            {{ htmlspecialchars($item['index_fuzzy']['range_akhir']) }}</td>
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
