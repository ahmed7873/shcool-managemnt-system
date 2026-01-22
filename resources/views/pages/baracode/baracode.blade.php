@extends('layouts.master2')
@section('css')
    @toastr_css
@endsection
@section('content')
    <div class="container p-3">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('baracoe_store') }}" method="post" id="form">
            @csrf
            <input type="hidden" name="student_id" id="student_id">
            <a href="{{ route('selection') }}">الصفحة الرئيسية</a>
            <div class="card p-3"
                style="border-radius: 8px; justify-content: center; align-items: center;display: flex; flex-direction: column; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%)">
                <h1 class="mb-5">{{ $setting['setting']['school_name'] }}</h1>
                @if ($setting['setting']['logo'])
                    <img src="{{ URL('/attachments/logo/' . $setting['setting']['logo']) }}" alt="">
                @endif
            </div>
        </form>
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
    <script>
        let pageFocused = true;

        window.addEventListener("focus", () => {
            pageFocused = true;
        });

        window.addEventListener("blur", () => {
            pageFocused = false;
        });
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                // console.warn("⚠️ الصفحة فقدت النشاط");
                pageFocused = false;
            }
        });


        let buffer = "";
        let lastTime = 0;
        document.addEventListener("keydown", (e) => {
            // prevent duplicate handling
            if (!pageFocused) return;
            if (e.repeat) return;

            const now = Date.now();

            // reset if typing is slow (human)
            if (now - lastTime > 100) buffer = "";

            lastTime = now;

            if (e.key === "Enter") {
                if (buffer.length > 0) {
                    // console.log("SCANNED:", buffer);
                    document.getElementById('student_id').value = buffer;
                    document.getElementById('form').submit();
                }
                buffer = "";
                return;
            }

            if (e.key.length === 1) {
                buffer += e.key;
            }
        });
    </script>
@endsection
