const products = document.getElementById('products');

if(products) {
    products.addEventListener('click', e => {
        if(e.target.className === 'btn btn-danger delete-product') {
            if(confirm('Are you sure to Delete the product')) {
                const id = e.target.getAttribute('data-id');
                
                fetch(`/product/${id}/delete`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}