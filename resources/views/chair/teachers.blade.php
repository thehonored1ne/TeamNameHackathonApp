<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Add Teacher Form --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Teacher</h3>
                <form method="POST" action="{{ route('chair.teachers.add') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="e.g. Juan Dela Cruz"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="e.g. juan@school.edu"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expertise Areas</label>
                            <input type="text" name="expertise_areas" value="{{ old('expertise_areas') }}"
                                placeholder="e.g. Programming|Mathematics"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <p class="text-xs text-gray-400 mt-1">Separate with pipe |</p>
                            @error('expertise_areas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Units</label>
                            <input type="number" name="max_units" value="{{ old('max_units', 21) }}"
                                min="1" max="30"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('max_units')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password"
                                placeholder="Minimum 8 characters"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Availability --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability (optional)</label>
                        <div id="availability-rows" class="space-y-2">
                            <div class="flex items-center gap-3">
                                <select name="days[]" class="border border-gray-300 rounded px-2 py-2 text-sm w-36">
                                    <option value="">-- Day --</option>
                                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                                <input type="time" name="time_starts[]" class="border border-gray-300 rounded px-2 py-2 text-sm">
                                <span class="text-gray-400 text-sm">to</span>
                                <input type="time" name="time_ends[]" class="border border-gray-300 rounded px-2 py-2 text-sm">
                                <button type="button" onclick="addAvailabilityRow()"
                                    class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 text-xs">
                                    + Add Row
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Add Teacher
                        </button>
                    </div>
                </form>
            </div>

            {{-- Teachers List --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    All Teachers
                    <span class="text-sm font-normal text-gray-500 ml-2">{{ $teachers->count() }} total</span>
                </h3>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Expertise Areas</th>
                            <th class="px-4 py-3">Max Units</th>
                            <th class="px-4 py-3">Assigned Subjects</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($teachers as $teacher)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $teacher->user->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $teacher->user->email }}</td>
                            <td class="px-4 py-3">
                                @foreach(explode('|', $teacher->expertise_areas) as $area)
                                    @if(!empty(trim($area)))
                                    <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs mr-1 mb-1">
                                        {{ trim($area) }}
                                    </span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-4 py-3">{{ $teacher->max_units }}</td>
                            <td class="px-4 py-3">{{ $teacher->assignments->count() }}</td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('chair.teachers.edit', $teacher->id) }}"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('chair.teachers.delete', $teacher->id) }}"
                                    onsubmit="return confirm('Are you sure you want to delete {{ $teacher->user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                No teachers yet. Add one above or upload a CSV.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        function addAvailabilityRow() {
            const container = document.getElementById('availability-rows');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3';
            div.innerHTML = `
                <select name="days[]" class="border border-gray-300 rounded px-2 py-2 text-sm w-36">
                    <option value="">-- Day --</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                </select>
                <input type="time" name="time_starts[]" class="border border-gray-300 rounded px-2 py-2 text-sm">
                <span class="text-gray-400 text-sm">to</span>
                <input type="time" name="time_ends[]" class="border border-gray-300 rounded px-2 py-2 text-sm">
                <button type="button" onclick="this.parentElement.remove()"
                    class="px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 text-xs">
                    Remove
                </button>
            `;
            container.appendChild(div);
        }
    </script>

</x-app-layout>