import PageHeader from '../components/PageHeader'

function NotFound() {
  return (
    <div className="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
      <PageHeader title="Página não encontrada" />
      <div className="rounded-2xl border border-surface1 bg-surface/70 p-10 text-center backdrop-blur-sm">
        <p className="text-6xl">🧭</p>
        <p className="mt-4 text-lg">O caminho que você tentou acessar não existe.</p>
      </div>
    </div>
  )
}

export default NotFound
