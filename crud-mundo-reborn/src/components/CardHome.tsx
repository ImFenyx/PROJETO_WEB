import { Link } from "react-router";

type CardProps = {
  title: string;
  description: string;
  link: string;
  emoji?: string;
  onHover?: (hover : boolean) => void
}

function Card({ title, description, link, emoji, onHover }: CardProps) {
  return (
    <div onMouseEnter={() => onHover?.(true)} onMouseLeave={() => onHover?.(false)} className="flex flex-col rounded-2xl border border-surface1 bg-surface/75 p-4 backdrop-blur-sm transition-all duration-200 ease-in-out select-none hover:scale-110">
      <div className="mb-2 flex gap-2 items-center">
        {emoji && <span aria-hidden="true">{emoji}</span>}
        <h2 className="text-2xl font-bold">{title}</h2>
      </div>
      <div className="mb-2 text-subtext">
        <p>{description}</p>
      </div>
      <div className="flex justify-end mt-auto">
        <Link to={link} className="w-full md:w-fit text-center bg-primary text-background font-semibold px-5 py-2.5 rounded-xl hover:brightness-110 active:scale-[0.98] transition-all select-none cursor-pointer">Ver mais</Link>
      </div>
    </div>

  )
}

export default Card
