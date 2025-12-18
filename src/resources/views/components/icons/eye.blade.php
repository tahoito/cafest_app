<button
  type="button"
  {{ $attributes->merge(['class' => 'inline-block password-toggle']) }}
  data-toggle-target="{{ $target ?? '' }}"
  aria-pressed="false"
  title="パスワード表示/非表示"
>
  <!-- Eye (visible) -->
  <span class="eye-icon" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
      <path d="M10.7325 5.076C13.0619 4.7984 15.4181 5.29082 17.4414 6.47805C19.4647 7.66528 21.0437 9.48208 21.9375 11.651C22.0209 11.8755 22.0209 12.1225 21.9375 12.347C21.57 13.238 21.0843 14.0755 20.4935 14.837M14.0835 14.158C13.5177 14.7045 12.7599 15.0069 11.9733 15C11.1867 14.9932 10.4343 14.6777 9.87807 14.1215C9.32185 13.5652 9.00634 12.8128 8.99951 12.0262C8.99267 11.2396 9.29505 10.4818 9.84153 9.916M17.4785 17.499C16.152 18.2848 14.672 18.776 13.1389 18.9394C11.6058 19.1028 10.0555 18.9345 8.59316 18.4459C7.13084 17.9573 5.79072 17.1599 4.66374 16.1077C3.53676 15.0556 2.64928 13.7734 2.06153 12.348C1.97819 12.1235 1.97819 11.8765 2.06153 11.652C2.94816 9.50186 4.5082 7.69725 6.50753 6.509M1.99953 2L21.9995 22" stroke="#666666" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </span>

    <!-- Eye Off (hidden) -->
  <span class="eye-off-icon" aria-hidden="true" style="display:none">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
        <path d="M2.06153 12.3484C1.97819 12.1238 1.97819 11.8769 2.06153 11.6524C2.87323 9.68421 4.25104 8.0014 6.0203 6.81726C7.78955 5.63312 9.87057 5.00098 11.9995 5.00098C14.1285 5.00098 16.2095 5.63312 17.9788 6.81726C19.748 8.0014 21.1258 9.68421 21.9375 11.6524C22.0209 11.8769 22.0209 12.1238 21.9375 12.3484C21.1258 14.3165 19.748 15.9993 17.9788 17.1835C16.2095 18.3676 14.1285 18.9997 11.9995 18.9997C9.87057 18.9997 7.78955 18.3676 6.0203 17.1835C4.25104 15.9993 2.87323 14.3165 2.06153 12.3484Z" stroke="#666666" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M11.9995 15.0004C13.6564 15.0004 14.9995 13.6572 14.9995 12.0004C14.9995 10.3435 13.6564 9.00036 11.9995 9.00036C10.3427 9.00036 8.99953 10.3435 8.99953 12.0004C8.99953 13.6572 10.3427 15.0004 11.9995 15.0004Z" stroke="#666666" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </span>
</button>

<script>
if (!window.__passwordToggleInitialized) {
  window.__passwordToggleInitialized = true;
  document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('.password-toggle');
    if (!btn) return;

    var targetSelector = btn.dataset.toggleTarget && btn.dataset.toggleTarget.trim();
    var input = null;

    if (targetSelector) {
      try {
        input = document.querySelector(targetSelector) || document.getElementById(targetSelector.replace(/^#/, ''));
      } catch (err) {
        input = null;
      }
    }

    if (!input) {
      var form = btn.closest('form');
      if (form) {
        input = form.querySelector('input[type="password"], input[data-password-input]');
      }
    }

    if (!input) {
      var prev = btn.previousElementSibling;
      if (prev && prev.tagName === 'INPUT') input = prev;
    }

    if (!input) return;

    var isHidden = input.type === 'password';
    try { input.type = isHidden ? 'text' : 'password'; } catch (err) {}
    btn.setAttribute('aria-pressed', String(isHidden));

    var eye = btn.querySelector('.eye-icon');
    var eyeOff = btn.querySelector('.eye-off-icon');
    if (eye && eyeOff) {
      eye.style.display = isHidden ? 'none' : '';
      eyeOff.style.display = isHidden ? '' : 'none';
    }
  });
}
</script>