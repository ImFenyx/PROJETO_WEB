import { Link } from 'react-router'

type PageHeaderProps = {
  emoji?: string
  title: string
}

function PageHeader({ emoji, title }: PageHeaderProps) {
  return (
    <div className="flex items-center justify-between gap-4">
      <h1 className="text-3xl font-serif italic text-primary sm:text-4xl">
        {emoji ? `${emoji} ` : ''}
        {title}
      </h1>
      <Link
        to="/"
        className="text-subtext hover:text-primary transition-colors text-sm shrink-0"
      >
        &larr; Voltar
      </Link>
    </div>
  )
}

export default PageHeader
