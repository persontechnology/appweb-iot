
<div>
    <input type="checkbox" 
           value="{{ $user->id }}" 
           {{ (is_array(old('user')) && in_array($user->id, old('user'))) ? 'checked' : '' }} 
           name="user[]"  
           class="form-check-input @error('user') is-invalid @enderror" 
           id="user-{{ $user->id }}">
</div>

