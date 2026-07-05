<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit & Activity Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Riwayat Perubahan Data (Log Sistem)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktor (User)</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Model</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Perubahan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($logs as $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 whitespace-nowrap text-gray-600">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                        <td class="py-3 px-4 font-semibold text-indigo-600">{{ $log->causer->name ?? 'Sistem / Anonim' }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $color = match($log->event) {
                                                    'created' => 'bg-green-100 text-green-800',
                                                    'updated' => 'bg-blue-100 text-blue-800',
                                                    'deleted' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $color }} uppercase">{{ $log->event }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600 text-xs font-mono">
                                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                        </td>
                                        <td class="py-3 px-4 text-xs text-gray-500 max-w-xs truncate" title="{{ json_encode($log->properties) }}">
                                            @if(isset($log->properties['attributes']))
                                                <span class="text-blue-500">Data Baru:</span> {{ json_encode($log->properties['attributes']) }}
                                            @endif
                                            @if(isset($log->properties['old']))
                                                <br>
                                                <span class="text-red-500">Data Lama:</span> {{ json_encode($log->properties['old']) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 text-center text-gray-500">Belum ada riwayat aktivitas yang tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
