export function toggleAll(source){
    document.querySelectorAll('input[name="selected[]"]').forEach(
       box => box.checked = source.checked
    );
  }
  