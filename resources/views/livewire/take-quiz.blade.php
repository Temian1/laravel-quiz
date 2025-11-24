<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($showResults)
        <!-- Results View -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                @if($attempt->passed)
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-green-600 mb-2">Congratulations!</h2>
                    <p class="text-gray-600 mb-6">You passed the quiz!</p>
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-red-600 mb-2">Quiz Complete</h2>
                    <p class="text-gray-600 mb-6">Keep practicing to improve your score!</p>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-blue-600">{{ $attempt->score }}</p>
                        <p class="text-sm text-gray-600">Your Score</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-purple-600">{{ $attempt->total_points }}</p>
                        <p class="text-sm text-gray-600">Total Points</p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-indigo-600">{{ $attempt->percentage }}%</p>
                        <p class="text-sm text-gray-600">Percentage</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-gray-600">{{ gmdate('i:s', $attempt->time_taken) }}</p>
                        <p class="text-sm text-gray-600">Time Taken</p>
                    </div>
                </div>

                <a href="/" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Back to Quizzes
                </a>
            </div>
        </div>
    @else
        <!-- Quiz Taking View -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold">{{ $quiz->title }}</h2>
                    @if($quiz->time_limit && $timeRemaining)
                        <div class="flex items-center space-x-2" id="timer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xl font-mono" x-data="timer({{ $timeRemaining }})" x-text="formatTime()" x-init="startTimer()"></span>
                        </div>
                    @endif
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}</span>
                        <span>{{ number_format($progress, 0) }}% Complete</span>
                    </div>
                    <div class="w-full bg-blue-800 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>

            @if($currentQuestion)
                <!-- Question Content -->
                <div class="p-8">
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $currentQuestion->question_text }}</h3>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $currentQuestion->points }} {{ Str::plural('point', $currentQuestion->points) }}
                            </span>
                        </div>
                    </div>

                    <!-- Answers -->
                    <div class="space-y-3">
                        @foreach($currentQuestion->answers as $answer)
                            <label class="block cursor-pointer">
                                <div class="border-2 rounded-lg p-4 transition-all duration-200 {{ isset($userAnswers[$currentQuestion->id]) && $userAnswers[$currentQuestion->id] == $answer->id ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                                    <div class="flex items-center">
                                        <input 
                                            type="radio" 
                                            name="question_{{ $currentQuestion->id }}"
                                            value="{{ $answer->id }}"
                                            wire:click="selectAnswer({{ $currentQuestion->id }}, {{ $answer->id }})"
                                            {{ isset($userAnswers[$currentQuestion->id]) && $userAnswers[$currentQuestion->id] == $answer->id ? 'checked' : '' }}
                                            class="w-5 h-5 text-blue-600"
                                        >
                                        <span class="ml-3 text-gray-900">{{ $answer->answer_text }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation -->
                <div class="bg-gray-50 px-8 py-4 flex justify-between items-center">
                    <button 
                        wire:click="previousQuestion"
                        @if($currentQuestionIndex == 0) disabled @endif
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Previous
                    </button>

                    <!-- Question Navigation Dots -->
                    <div class="flex space-x-2 overflow-x-auto">
                        @foreach($questions as $index => $question)
                            <button 
                                wire:click="goToQuestion({{ $index }})"
                                class="w-8 h-8 rounded-full text-sm font-medium transition-colors {{ $index == $currentQuestionIndex ? 'bg-blue-600 text-white' : (isset($userAnswers[$question->id]) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700') }}"
                                title="Question {{ $index + 1 }}"
                            >
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    @if($currentQuestionIndex < $totalQuestions - 1)
                        <button 
                            wire:click="nextQuestion"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Next
                        </button>
                    @else
                        <button 
                            wire:click="submitQuiz"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        >
                            Submit Quiz
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Timer Script -->
        @if($quiz->time_limit && $timeRemaining)
            <script>
                function timer(initialSeconds) {
                    return {
                        seconds: initialSeconds,
                        interval: null,
                        
                        startTimer() {
                            this.interval = setInterval(() => {
                                if (this.seconds > 0) {
                                    this.seconds--;
                                } else {
                                    clearInterval(this.interval);
                                    @this.call('timeExpired');
                                }
                            }, 1000);
                        },
                        
                        formatTime() {
                            const minutes = Math.floor(this.seconds / 60);
                            const secs = this.seconds % 60;
                            return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                        }
                    }
                }
            </script>
            <script src="//unpkg.com/alpinejs" defer></script>
        @endif
    @endif
</div>
