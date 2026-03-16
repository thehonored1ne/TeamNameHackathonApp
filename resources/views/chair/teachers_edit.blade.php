<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Teacher — {{ $teacher->user->name }}</h3>

                <form method="POST" action="{{ route('chair.teachers.update', $teacher->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $teacher->user->name) }}"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $teacher->user->email) }}"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expertise Areas</label>
                            <input type="text" name="expertise_areas" value="{{ old('expertise_areas', $teacher->expertise_areas) }}"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <p class="text-xs text-gray-400 mt-1">Separate with pipe |</p>
                            @error('expertise_areas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Units</label>
                            <input type="number" name="max_units" value="{{ old('max_units', $teacher->max_units) }}"
                                min="1" max="30"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            @error('max_units')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Availability --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability Schedule</label>
                        <div id="availability-rows" class="space-y-2">
                            @forelse($teacher->availabilities as $availability)
                            <div class="flex items-center gap-3">
                                <select name="days[]" class="border border-gray-300 rounded px-2 py-2 text-sm w-36">
                                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                                        <option value="{{ $day }}" {{ $availability->day === $day ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="time" name="time_starts[]" value="{{ $availability->time_start }}"
                                    class="border border-gray-300 rounded px-2 py-2 text-sm">
                                <span class="text-gray-400 text-sm">to</span>
                                <input type="time" name="time_ends[]" value="{{ $availability->time_end }}"
                                    class="border border-gray-300 rounded px-2 py-2 text-sm">
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 text-xs">
                                    Remove
                                </button>
                            </div>
                            @empty
                            <p class="text-sm text-gray-400">No availability set.</p>
                            @endforelse
                        </div>
                        <button type="button" onclick="addAvailabilityRow()"
                            class="mt-2 px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 text-xs">
                            + Add Slot
                        </button>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('chair.teachers') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                            ← Back
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
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