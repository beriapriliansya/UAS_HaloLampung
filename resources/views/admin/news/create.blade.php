<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ isset($newsItem) ? __('Edit Berita') : __('Tambah Berita Baru') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($newsItem) ? route('admin.news.update', $newsItem) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($newsItem))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="title" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $newsItem->title ?? '') }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label">Kutipan Singkat (Excerpt)</label>
                    <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $newsItem->excerpt ?? '') }}</textarea>
                    @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Konten Berita <span class="text-danger">*</span></label>
                    {{-- Ganti dengan editor WYSIWYG jika perlu --}}
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content', $newsItem->content ?? '') }}</textarea>
                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="featured_image" class="form-label">Gambar Utama (Featured Image)</label>
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image" accept="image/*">
                        @error('featured_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(isset($newsItem) && $newsItem->featured_image)
                            <div class="mt-2">
                                <img src="{{ $newsItem->featured_image_url }}" alt="Current Image" width="150" class="img-thumbnail">
                                <small class="d-block text-muted">Gambar saat ini. Pilih file baru untuk mengganti.</small>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="published_at" class="form-label">Tanggal Publikasi (Kosongkan untuk sekarang)</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', isset($newsItem) && $newsItem->published_at ? $newsItem->published_at->format('Y-m-d\TH:i') : '') }}">
                        @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published', $newsItem->is_published ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Publikasikan Berita</label>
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($newsItem) ? 'Update Berita' : 'Simpan Berita' }}</button>
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
     {{-- Jika menggunakan editor WYSIWYG, tambahkan scriptnya di sini --}}
     {{-- @push('scripts')
         <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
         <script>
             tinymce.init({
                 selector: 'textarea#content', // Targetkan textarea konten
                 plugins: 'code table lists image link media',
                 toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link media'
             });
         </script>
     @endpush --}}
</x-admin-layout>