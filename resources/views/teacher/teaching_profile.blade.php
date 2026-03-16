<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teaching Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(!$teacherProfile)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    No teaching profile found. Please contact your Program Chair.
                </div>
            @else

            <form method="POST" action="{{ route('teacher.teaching.profile.update') }}">
                @csrf
                @method('PATCH')

                {{-- Expertise and Max Units --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Expertise & Load</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expertise Areas</label>
                        <input type="text" name="expertise_areas"
                            value="{{ $teacherProfile->expertise_areas }}"
                            placeholder="e.g. Programming|Mathematics|Database"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Separate multiple areas with a pipe | symbol. e.g. Programming|Mathematics</p>
                        @error('expertise_areas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Units</label>
                        <input type="number" name="max_units"
                            value="{{ $teacherProfile->max_units }}"
                            min="1" max="30"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Maximum teaching units you can handle per semester.</p>
                        @error('max_units')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Availability --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800">Availability Schedule</h3>
                    <p class="text-sm text-gray-500">Set the days and times you are available to teach.</p>

                    <div id="availability-rows" class="space-y-3">
                        @forelse($teacherProfile->availabilities as $index => $availability)
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
                        </div>
                        @empty
                        <p class="text-sm text-gray-400">No availability set yet. Add a slot below.</p>
                        @endforelse
                    </div>

                    {{-- Add new row button --}}
                    <div id="new-rows"></div>
                    <button type="button" onclick="addRow()"
                        class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 text-sm">
                        + Add Availability Slot
                    </button>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Save Changes
                    </button>
                </div>

            </form>
            @endif

        </div>
    </div>

    <script>
        function addRow() {
            const container = document.getElementById('new-rows');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 mt-3';
            div.innerHTML = `
                <select name="days[]" class="border border-gray-300 rounded px-2 py-2 text-sm w-36">
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
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 text-sm">Remove</button>
            `;
            container.appendChild(div);
        }
    </script>

</x-app-layout>