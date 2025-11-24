<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Quiz Management</h2>
        <p class="mt-2 text-gray-600">Create and manage quizzes</p>
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

    <!-- Quiz Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4">{{ $isEditing ? 'Edit Quiz' : 'Create New Quiz' }}</h3>
        
        <form wire:submit.prevent="{{ $isEditing ? 'updateQuiz' : 'createQuiz' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input 
                        type="text" 
                        wire:model="title" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        wire:model="description" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Limit (minutes)</label>
                    <input 
                        type="number" 
                        wire:model="time_limit" 
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    @error('time_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pass Percentage *</label>
                    <input 
                        type="number" 
                        wire:model="pass_percentage" 
                        min="0" 
                        max="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    @error('pass_percentage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model="is_active" 
                        id="is_active"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex space-x-3">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    {{ $isEditing ? 'Update Quiz' : 'Create Quiz' }}
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

    <!-- Quizzes List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold">Existing Quizzes</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Limit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pass %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $quiz->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $quiz->questions_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $quiz->time_limit ?? 'No limit' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $quiz->pass_percentage }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $quiz->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="/admin/quiz/{{ $quiz->id }}/questions" class="text-blue-600 hover:text-blue-900">Questions</a>
                                <button wire:click="editQuiz({{ $quiz->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button 
                                    wire:click="deleteQuiz({{ $quiz->id }})" 
                                    onclick="return confirm('Are you sure you want to delete this quiz?')"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No quizzes found. Create your first quiz above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
