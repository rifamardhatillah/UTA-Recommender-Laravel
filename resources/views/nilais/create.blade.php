{{-- resources/views/nilais/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Input Nilai Alternatif')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
    <div class="max-w-2xl mx-auto px-4 mt-8 mb-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-700">Input Nilai untuk Alternatif</h4>
            </div>

            <form action="{{ route('nilais.store') }}" method="POST" class="p-6">
                @csrf

                <div class="mb-4">
                    <label for="alternatif_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Alternatif <span class="text-red-500">*</span></label>
                    <select name="alternatif_id" id="alternatif_id" class="form-select block w-full px-3 py-2 text-base text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none @error('alternatif_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Alternatif --</option>
                        @foreach ($alternatifs as $id => $nama)
                            <option value="{{ $id }}" {{ old('alternatif_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('alternatif_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($kriterias->count() > 0)
                    <h5 class="text-md font-semibold text-gray-700 mb-2 mt-6">Masukkan Nilai Kriteria:</h5>
                    @foreach ($kriterias as $kriteria)
                        <div class="mb-4">
                            <label for="nilai_{{ $kriteria->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $kriteria->nama_kriteria }} ({{ $kriteria->tipe }})
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="nilai[{{ $kriteria->id }}]" id="nilai_{{ $kriteria->id }}"
                                   class="form-input block w-full px-3 py-2 text-base text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none @error('nilai.' . $kriteria->id) border-red-500 @enderror"
                                   value="{{ old('nilai.' . $kriteria->id) }}"
                                   placeholder="Masukkan nilai untuk {{ $kriteria->nama_kriteria }}" required>
                            @error('nilai.' . $kriteria->id)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                @else
                    <p class="text-yellow-600 bg-yellow-100 border-l-4 border-yellow-500 p-4 my-4">
                        Belum ada data kriteria. Silakan tambahkan kriteria terlebih dahulu sebelum menginput nilai.
                    </p>
                @endif


                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('nilais.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md shadow-sm">
                        Batal
                    </a>
                    @if($kriterias->count() > 0)
                    <button type="submit" class="bg-custom-purple hover:bg-custom-purple-dark text-white font-semibold py-2 px-4 rounded-md shadow-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Nilai
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection