import './App.css';
import { BrowserRouter } from 'react-router-dom'
import Navigator from './components/Navigator';
import { ProductProvider } from './context_apis/ProductsContext';
import { CartBoxProvider } from './context_apis/CartBoxContext';
import { CartSizeProvider } from './context_apis/CartSizeContext';

function App() {
  return (
    <BrowserRouter>
      <ProductProvider>
        <CartBoxProvider>
          <CartSizeProvider>
            <Navigator />
          </CartSizeProvider>
        </CartBoxProvider>
      </ProductProvider>
    </BrowserRouter>
  );
}

export default App;
