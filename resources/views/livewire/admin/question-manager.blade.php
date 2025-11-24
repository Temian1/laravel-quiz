<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $quiz->title }} - Questions</h2>
                <p class="mt-2 text-gray-600">Manage questions for this quiz</p>
            </div>
            <a href="/admin" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                Back to Quizzes
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Question Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4">{{ $isEditing ? 'Edit Question' : 'Add New Question' }}</h3>
        
        <form wire:submit.prevent="{{ $isEditing ? 'updateQuestion' : 'createQuestion' }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Question Text *</label>
                <textarea 
                    wire:model="question_text" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                ></textarea>
                @error('question_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Points *</label>
                <input 
                    type="number" 
                    wire:model="points" 
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Answers -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Answers * (at least 2 required)</label>
                
                @foreach($answers as $index => $answer)
                    <div class="flex items-center space-x-2 mb-3">
                        <input 
                            type="checkbox" 
                            wire:model="answers.{{ $index }}.is_correct"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            title="Mark as correct answer"
                        >
                        <input 
                            type="text" 
                            wire:model="answers.{{ $index }}.text" 
                            placeholder="Answer text"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                        @if(count($answers) > 2)
                            <button 
                                type="button"
                                wire:click="removeAnswer({{ $index }})" 
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                            >
                                Remove
                            </button>
                        @endif
                    </div>
                    @error('answers.' . $index . '.text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @endforeach

                <button 
                    type="button"
                    wire:click="addAnswer" 
                    class="mt-2 px-4 py-2 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50"
                >
                    + Add Answer
                </button>
            </div>

            <div class="flex space-x-3">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    {{ $isEditing ? 'Update Question' : 'Add Question' }}
                </button>
                
                @if($isEditing)
                    <button 
                        type="button"
                        wire:click="resetForm" 
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Questions List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold">Existing Questions ({{ count($questions) }})</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($questions as $q)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-start space-x-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium mb-2">{{ $q->question_text }}</p>
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ $q->points }} {{ Str::plural('point', $q->points) }}
                                    </span>
                                    
                                    <!-- Answers -->
                                    <div class="mt-3 space-y-2">
                                        @foreach($q->answers as $ans)
                                            <div class="flex items-center space-x-2 text-sm">
                                                @if($ans->is_correct)
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                <span class="{{ $ans->is_correct ? 'text-green-800 font-medium' : 'text-gray-600' }}">
                                                    {{ $ans->answer_text }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 ml-4">
                            <button 
                                wire:click="editQuestion({{ $q->id }})" 
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                Edit
                            </button>
                            <button 
                                wire:click="deleteQuestion({{ $q->id }})" 
                                onclick="return confirm('Are you sure you want to delete this question?')"
                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No questions found. Add your first question above.
                </div>
            @endforelse
        </div>
    </div>
</div>
