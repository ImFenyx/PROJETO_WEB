
import Card from '../components/CardHome'
import { useState } from 'react'

export default function Home() {
  const [hovered, setHovered] = useState(false)
  
  return (
    <div className='flex flex-col min-h-screen items-center justify-center bg-hero transition-[--hero-to] duration-200' style={{ '--hero-to': hovered ? 'var(--color-primary)' : 'var(--color-hero-to)' } as React.CSSProperties}
>
      <div><h1>CRUD Mundo</h1></div>
      <div className='mx-auto w-full max-w-6xl px-4 py-8'>
        <div className='grid grid-cols-2 gap-4 mt-4 p-4 group'>
          <Card title="Países" description="Visualizar, cadastrar, editar ou remover os países do banco de dados." link='paises' onHover={setHovered}/>
          <Card title="Cidades" description="Visualizar, cadastrar, editar ou remover os continentes do banco de dados." link='cidades' onHover={setHovered}/>
          <Card title="Continentes" description="Visualizar, cadastrar, editar ou remover os continentes do banco de dados." link='continentes' onHover={setHovered}/>
          <Card title="Governantes" description="Visualizar, cadastrar, editar ou remover os governantes do banco de dados." link='governantes' onHover={setHovered}/>
        </div>
      </div>
    </div>
  )
}
