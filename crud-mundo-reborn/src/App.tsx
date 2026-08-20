import { Routes, Route } from 'react-router'
import Home from './pages/Home'
import Paises from './pages/Paises'
import Cidades from './pages/Cidades'
import Continentes from './pages/Continentes'
import Governantes from './pages/Governantes'
import NotFound from './pages/NotFound'


function App() {
  return (
    <Routes>
      <Route path='/' element={<Home />} />
      <Route path='/paises' element={<Paises />} />
      <Route path='/cidades' element={<Cidades />} />
      <Route path='/continentes' element={<Continentes />} />
      <Route path='/governantes' element={<Governantes />} />
      <Route path='*' element={<NotFound />} />
    </Routes>
  )
}

export default App
