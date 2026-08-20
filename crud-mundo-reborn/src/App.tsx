import { Routes, Route } from 'react-router'
import Home from './pages/Home'
import Paises from './pages/Paises'
import Cidades from './pages/Cidades'
import Continentes from './pages/Continentes'
import Governantes from './pages/Governantes'


function App() {
  return (
      <Routes>
        <Route path='/' element={<Home />}></Route>
        <Route path='/paises' element={<Paises />}></Route>
        <Route path='/cidades' element={<Cidades />}></Route>
        <Route path='/continentes' element={<Continentes />}></Route>
        <Route path='/governantes' element={<Governantes />}></Route>
      </Routes>

  )
}

export default App
