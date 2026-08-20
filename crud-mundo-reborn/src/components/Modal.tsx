import type { ReactNode } from 'react'

type ModalProps = {
  title: string
  onClose: () => void
  children: ReactNode
}

function Modal({ title, onClose, children }: ModalProps) {
  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
    >
      <div
        className="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl border border-surface1 bg-surface p-6 shadow-xl"
        onClick={(event) => event.stopPropagation()}
      >
        <div className="mb-4 flex items-center justify-between gap-4">
          <h2 className="text-xl font-bold text-primary">{title}</h2>
          <button
            type="button"
            onClick={onClose}
            aria-label="Fechar"
            className="text-subtext hover:text-text transition-colors text-lg leading-none"
          >
            ✕
          </button>
        </div>
        {children}
      </div>
    </div>
  )
}

export default Modal
