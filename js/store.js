/* /js/store.js
 * Handles AJAX add‑to‑cart + toast notification.
 * Requires every product‑detail page to have:
 *   <form class="add-to-cart-form" method="post" action="#">
 *     … hidden add_id, quantity input, <button type="submit"> …
 *   </form>
 */

(function () {
    /* ---------- utilities ---------- */
    function $(sel, el = document) { return el.querySelector(sel); }

    /* Ensure a single toast container exists */
    let toastContainer = $('#toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText =
            'position:fixed;top:1rem;right:1rem;z-index:9999;';
        document.body.appendChild(toastContainer);
    }

    function showToast(msg) {
        const t = document.createElement('div');
        t.textContent = msg;
        t.style.cssText = `
            background:#333;color:#fff;padding:.75rem 1rem;
            margin-bottom:.5rem;border-radius:.5rem;
            box-shadow:0 2px 6px rgba(0,0,0,.2);opacity:0;transition:opacity .3s;
        `;
        toastContainer.appendChild(t);
        requestAnimationFrame(() => (t.style.opacity = '1'));      // fade in
        setTimeout(() => {
            t.style.opacity = '0';
            t.addEventListener('transitionend', () => t.remove());
        }, 3000);
    }

    /* ---------- main ---------- */
    const form = $('.add-to-cart-form');
    if (!form) return;                 // nothing to do on pages without the form

    const btn = $('#add-btn', form);   // optional, only for disabling/enabling

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (btn) btn.disabled = true;

        try {
            const res = await fetch('/store/add_to_cart.php', {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin',
            });
            const data = await res.json();
            if (data.status === 'ok') {
                showToast('Item added to cart');
            } else {
                showToast('Error: ' + (data.error || 'unknown'));
            }
        } catch (err) {
            showToast('Network error');
        } finally {
            if (btn) btn.disabled = false;
        }
    });
})();
