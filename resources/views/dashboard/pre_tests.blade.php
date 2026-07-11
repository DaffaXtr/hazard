@extends('dashboard.layout')

@section('title', 'Hasil Pre-Test')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white md:text-3xl">Hasil Ujian Pre-Test</h2>
        <p class="text-slate-400 text-sm mt-1">Riwayat pengerjaan pre-test teori K3 sebelum memulai simulasi AR.</p>
    </div>

    <!-- MOBILE CARD LAYOUT (Visible only on screens < 640px) -->
    <div class="block sm:hidden space-y-4">
        @forelse($preTests as $test)
            <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl space-y-4 shadow-lg">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700 text-teal-400 font-bold uppercase text-xs shrink-0">
                            {{ substr($test->user->name ?? 'M', 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-white text-sm truncate">{{ $test->user->name ?? 'Mahasiswa' }}</h4>
                            <p class="text-[10px] text-slate-500 truncate">{{ $test->user->email ?? '' }}</p>
                        </div>
                    </div>
                    <!-- Score Badge -->
                    <span class="px-3 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-full text-xs font-bold shrink-0">
                        Skor: {{ $test->score }}
                    </span>
                </div>

                <!-- Footer details -->
                <div class="flex items-center justify-between pt-2 border-t border-slate-800/85">
                    <span class="text-[10px] text-slate-550 font-semibold">{{ $test->created_at->format('d M Y H:i') }}</span>
                    
                    <button type="button" 
                            class="view-answers-btn flex items-center gap-1 text-[11px] text-teal-400 hover:text-teal-300 font-semibold uppercase tracking-wider transition-colors cursor-pointer"
                            data-student="{{ $test->user->name ?? 'Mahasiswa' }}"
                            data-score="{{ $test->score }}"
                            data-answers='@json($test->answers)'>
                        <span>Detail Jawaban</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-slate-900/30 border border-slate-850 p-8 rounded-2xl text-center space-y-3">
                <i data-lucide="file-check-2" class="w-12 h-12 text-slate-655 mx-auto"></i>
                <p class="text-sm text-slate-500 font-medium">Belum ada riwayat pre-test.</p>
            </div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE LAYOUT (Hidden on mobile, visible on sm and up) -->
    <div class="hidden sm:block bg-slate-900/40 backdrop-blur-xl border border-slate-800/80 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-[11px] text-slate-400 font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Mahasiswa</th>
                        <th class="py-4 px-6 text-center">Skor Ujian</th>
                        <th class="py-4 px-6 text-center">Tanggal Pengerjaan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($preTests as $test)
                        <tr class="hover:bg-slate-800/25 transition-colors group">
                            <!-- User info -->
                            <td class="py-4 px-6 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700 text-teal-400 font-bold uppercase text-xs">
                                    {{ substr($test->user->name ?? 'M', 0, 2) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-white truncate">{{ $test->user->name ?? 'Mahasiswa' }}</p>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ $test->user->email ?? '' }}</p>
                                </div>
                            </td>
                            <!-- Score -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-full text-xs font-bold">
                                    {{ $test->score }} / 100
                                </span>
                            </td>
                            <!-- Date -->
                            <td class="py-4 px-6 text-center text-xs text-slate-400 font-medium">
                                {{ $test->created_at->format('d F Y - H:i') }} WIB
                            </td>
                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <button type="button" 
                                        class="view-answers-btn inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-800 hover:bg-teal-500/10 text-slate-300 hover:text-teal-400 border border-slate-700 hover:border-teal-500/20 rounded-xl text-xs font-semibold tracking-wide transition-all duration-300 cursor-pointer"
                                        data-student="{{ $test->user->name ?? 'Mahasiswa' }}"
                                        data-score="{{ $test->score }}"
                                        data-answers='@json($test->answers)'>
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    <span>Lihat Detail</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500 font-medium space-y-3">
                                <i data-lucide="file-check-2" class="w-12 h-12 text-slate-655 mx-auto"></i>
                                <p>Belum ada riwayat pre-test.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- DETAILED ANSWERS MODAL -->
<div id="answersModal" class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden transition-opacity duration-350 opacity-0">
    <!-- Modal Card -->
    <div class="bg-slate-900 border border-slate-800/80 w-full max-w-lg rounded-3xl shadow-2xl flex flex-col max-h-[85vh] transform scale-95 transition-transform duration-350 overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-800/90 flex items-center justify-between shrink-0 bg-slate-950/25">
            <div>
                <h3 class="text-lg font-bold text-white">Detail Jawaban Pre-Test</h3>
                <p id="modalStudentName" class="text-xs text-slate-500 mt-0.5">Nama Mahasiswa</p>
            </div>
            <!-- Score badge in header -->
            <span id="modalStudentScore" class="px-3 py-1 bg-violet-500/15 border border-violet-500/20 text-violet-400 rounded-full text-xs font-extrabold">
                Skor: 0
            </span>
        </div>

        <!-- Answers Content (Scrollable) -->
        <div id="modalBody" class="p-6 overflow-y-auto space-y-5 grow">
            <!-- Dynamically populated via JS -->
        </div>

        <!-- Footer -->
        <div class="p-5 border-t border-slate-850 flex justify-end shrink-0 bg-slate-950/25">
            <button type="button" 
                    id="closeModalBtn" 
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700/80 rounded-xl text-xs font-semibold tracking-wide transition-all active:scale-95 cursor-pointer">
                Tutup Detail
            </button>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("answersModal");
        const modalBody = document.getElementById("modalBody");
        const modalStudentName = document.getElementById("modalStudentName");
        const modalStudentScore = document.getElementById("modalStudentScore");
        const closeModalBtn = document.getElementById("closeModalBtn");

        // Open Modal function
        function openModal(studentName, score, answers) {
            modalStudentName.textContent = studentName;
            modalStudentScore.textContent = `Skor: ${score}`;
            
            // Generate answers list
            modalBody.innerHTML = '';
            
            if (answers && Array.isArray(answers)) {
                answers.forEach((ans, index) => {
                    const isCorrect = ans.is_correct === true || ans.is_correct === "true" || ans.is_correct === 1;
                    const card = document.createElement('div');
                    card.className = "bg-slate-950/40 border border-slate-850 p-4.5 rounded-2xl space-y-3 relative overflow-hidden";
                    
                    card.innerHTML = `
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide shrink-0">Soal ${index + 1}</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-bold ${
                                isCorrect 
                                    ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' 
                                    : 'bg-rose-500/10 border border-rose-500/20 text-rose-450'
                            }">
                                <i data-lucide="${isCorrect ? 'check' : 'x'}" class="w-3 h-3"></i>
                                <span>${isCorrect ? 'BENAR' : 'SALAH'}</span>
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-white leading-relaxed">${ans.question || 'Pertanyaan K3'}</p>
                        <div class="p-3 bg-slate-900/60 border border-slate-850 rounded-xl">
                            <p class="text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1">Jawaban Mahasiswa:</p>
                            <p class="text-xs ${isCorrect ? 'text-emerald-300/90' : 'text-rose-300/90'} font-medium leading-normal">${ans.user_answer || '-'}</p>
                        </div>
                    `;
                    modalBody.appendChild(card);
                });
            } else {
                modalBody.innerHTML = `<p class="text-xs text-slate-500 text-center font-medium py-6">Detail jawaban tidak tersedia.</p>`;
            }

            // Refresh icons inside modal
            lucide.createIcons();

            // Animate display
            modal.classList.replace("hidden", "flex");
            setTimeout(() => {
                modal.classList.remove("opacity-0");
                modal.querySelector(".transform").classList.remove("scale-95");
            }, 10);
        }

        // Close Modal function
        function closeModal() {
            modal.classList.add("opacity-0");
            modal.querySelector(".transform").classList.add("scale-95");
            setTimeout(() => {
                modal.classList.replace("flex", "hidden");
            }, 300);
        }

        // Bind click events on all view buttons
        document.querySelectorAll(".view-answers-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const name = this.getAttribute("data-student");
                const score = this.getAttribute("data-score");
                let answers = [];
                try {
                    answers = JSON.parse(this.getAttribute("data-answers"));
                } catch(e) {
                    console.error("Failed to parse answers JSON", e);
                }
                openModal(name, score, answers);
            });
        });

        // Close listeners
        closeModalBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
</script>
@endsection
