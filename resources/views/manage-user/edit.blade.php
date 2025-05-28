<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="max-w-5xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
    <form action="{{ route('manage-user.update', $user->id) }}" class="space-y-4" method="POST">
      @csrf
      @method('PUT')
      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="role" class="w-full md:w-40 text-sm font-medium text-gray-900">Role</label>
        <div class="flex-1">
          <select name="role" id="role"
            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="" disabled {{ old('role', $user->role ?? '') ? '' : 'selected' }}>choose a role
            </option>
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>admin</option>
            <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>user</option>
          </select>
          @error('role')
            <small class="text-red-500">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="status" class="w-full md:w-40 text-sm font-medium text-gray-900">Status</label>
        <div class="flex-1">
          <select name="status" id="status"
            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>active
            </option>
            <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>inactive
            </option>
          </select>
          @error('status')
            <small class="text-red-500">{{ $message }}</small>
          @enderror
        </div>
      </div>



      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <div class="w-full md:w-40"></div>
        <div class="flex gap-2">
          <button type="button" onclick="window.location.href='{{ route('manage-user.index') }}'"
            class="bg-gray-500 text-sm text-white px-3 py-1 rounded-md uppercase">Back</button>
          <button type="submit" class="bg-blue-500 text-sm text-white px-3 py-1 rounded-md uppercase">Save</button>
        </div>
      </div>
    </form>
  </div>
</x-layout>
