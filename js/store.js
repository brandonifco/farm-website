/* /js/store.js
 * - Handles AJAX add‑to‑cart from product‑detail forms
 * - Handles “Add to Cart” quick‑add buttons on the product grid
 * - Shows a top‑right toast for 3 s
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
            box-shadow:0 2px 6px rgba(0,0,0,.2);
            opacity:0;transition:opacity .3s;
        `;
        toastContainer.appendChild(t);
        requestAnimationFrame(() => (t.style.opacity = '1'));      // fade in
        setTimeout(() => {
            t.style.opacity = '0';
            t.addEventListener('transitionend', () => t.remove());
        }, 3000);
    }

    async function postAdd(id, qty = 1) {
        const formData = new FormData();
        formData.append('add_id', id);
        formData.append('quantity', qty);

        const res  = await fetch('/store/add_to_cart.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });
        return res.json();          // { status:'ok' | 'error', … }
    }

    /* ---------- product‑detail form ---------- */
    const form = $('.add-to-cart-form');
    if (form) {
        const btn = $('#add-btn', form);

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (btn) btn.disabled = true;

            try {
                const data = await postAdd(form.add_id.value, form.quantity.value);
                if (data.status === 'ok') {
                    showToast('Item added to cart');
                } else {
                    showToast('Error: ' + (data.error || 'unknown'));
                }
            } catch {
                showToast('Network error');
            } finally {
                if (btn) btn.disabled = false;
            }
        });
    }

    /* ---------- quick‑add buttons on the product grid ---------- */
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.quick-add');
        if (!btn) return;                       // click wasn’t on a quick‑add button

        e.preventDefault();
        btn.disabled = true;                    // brief UX feedback

        try {
            const data = await postAdd(btn.dataset.id, 1);
            if (data.status === 'ok') {
                showToast('Item added to cart');
            } else {
                showToast('Error: ' + (data.error || 'unknown'));
            }
        } catch {
            showToast('Network error');
        } finally {
            btn.disabled = false;
        }
    });
})();
