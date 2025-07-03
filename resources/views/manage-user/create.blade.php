<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div
    class="max-w-5xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
    <form action="{{ route('manage-user.store') }}" class="space-y-4" method="POST">
      @csrf

      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="email"
          class="w-full md:w-40 text-sm font-medium text-gray-900 capitalize">Email</label>
        <div class="flex-1">
          <input type="email" name="email" id="email"
            class="w-full text-sm font-medium bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2 focus:ring-primary-600 focus:border-primary-600"
            placeholder="masukkan email" autocomplete="off" value="{{ old('email') }}">
          @error('email')
            <small class="text-red-500 text-xs font-medium">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="name"
          class="w-full md:w-40 text-sm font-medium text-gray-900 capitalize">Name</label>
        <div class="flex-1">
          <input type="text" name="name" id="name"
            class="w-full text-sm font-medium bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2 focus:ring-primary-600 focus:border-primary-600"
            placeholder="masukkan nama" autocomplete="off" value="{{ old('name') }}">
          @error('name')
            <small class="text-red-500 text-xs font-medium">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="password"
          class="w-full md:w-40 text-sm font-medium text-gray-900 capitalize">Password</label>
        <div class="flex-1">
          <input type="password" name="password" id="password"
            class="w-full text-sm font-medium bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2 focus:ring-primary-600 focus:border-primary-600"
            placeholder="masukkan password" autocomplete="off"
            value="{{ old('password') }}">
          @error('password')
            <small class="text-xs font-medium text-red-500">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="role"
          class="w-full md:w-40 text-sm font-medium text-gray-900">Role</label>
        <div class="flex-1">
          <select name="role" id="role"
            class="w-full bg-gray-50 border border-gray-300 text-sm font-medium text-gray-900 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="" disabled {{ old('role') ? '' : 'selected' }}>choose a
              role</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>admin
            </option>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>user
            </option>
          </select>
          @error('role')
            <small class="text-red-500 text-xs font-medium">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <label for="status"
          class="w-full md:w-40 text-sm font-medium text-gray-900">Status</label>
        <div class="flex-1">
          <select name="status" id="status"
            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm font-medium rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="active"
              {{ old('status', 'active') == 'active' ? 'selected' : '' }}>active</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
              inactive</option>
          </select>
          @error('status')
            <small class="text-red-500 text-xs font-medium">{{ $message }}</small>
          @enderror
        </div>
      </div>


      <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
        <div class="w-full md:w-40"></div>
        <div class="flex gap-2">
          <button type="button"
            onclick="window.location.href='{{ route('manage-user.index') }}'"
            class="bg-gray-500 text-sm font-medium text-white px-3 py-1 rounded-md uppercase">Back</button>
          <button type="submit"
            class="bg-blue-500 text-sm font-medium text-white px-3 py-1 rounded-md uppercase">Save</button>
        </div>
      </div>
    </form>
  </div>

</x-layout>
