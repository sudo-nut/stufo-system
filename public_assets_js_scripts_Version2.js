// public/assets/js/scripts.js
// Basic enhancements: debounce search and confirm delete

function debounce(fn, delay) {
  let t;
  return function(...args) {
    clearTimeout(t);
    t = setTimeout(() => fn.apply(this, args), delay);
  };
}

document.addEventListener('DOMContentLoaded', function() {
  const q = document.getElementById('q');
  if (q) {
    q.addEventListener('input', debounce(function(e) {
      // For nicer UX: submit after 600ms when typing
      const form = this.closest('form');
      if (form) {
        // optionally auto-submit:
        // form.submit();
      }
    }, 600));
  }
});

function confirmDelete() {
  return confirm('Are you sure you want to delete this student? This action cannot be undone.');
}