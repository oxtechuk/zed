import type { IBlogArticleHTMLProps } from "../../interfaces/IBlogArticleHTMLProps";

export default function BlogArticleHTML({ content }: IBlogArticleHTMLProps) {
    return (
        <article
            className="py-8 blog-content-html"
            dangerouslySetInnerHTML={{ __html: content }}
        />
    );
}
